<?php

declare(strict_types=1);

namespace Doctrine\ORM\Query;

use Doctrine\Common\Lexer\Token;
use Doctrine\Deprecations\Deprecation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\AST\Functions;
use Doctrine\ORM\Query\Exec\SqlFinalizer;
use LogicException;
use ReflectionClass;

use function array_intersect;
use function array_search;
use function assert;
use function class_exists;
use function count;
use function explode;
use function implode;
use function in_array;
use function interface_exists;
use function is_string;
use function sprintf;
use function str_contains;
use function strlen;
use function strpos;
use function strrpos;
use function strtolower;
use function substr;

/**
 * An LL(*) recursive-descent parser for the context-free grammar of the Doctrine Query Language.
 * Parses a DQL query, reports any errors in it, and generates an AST.
 *
 * @phpstan-import-type AssociationMapping from ClassMetadata
 * @phpstan-type DqlToken = Token<TokenType::T_*, string>
 * @phpstan-type QueryComponent = array{
 *                 metadata?: ClassMetadata<object>,
 *                 parent?: string|null,
 *                 relation?: AssociationMapping|null,
 *                 map?: string|null,
 *                 resultVariable?: AST\Node|string,
 *                 nestingLevel: int,
 *                 token: DqlToken,
 *             }
 */
class Parser
{
    /**
     * @readonly Maps BUILT-IN string function names to AST class names.
     * @var array<string, class-string<Functions\FunctionNode>>
     */
    private static $stringFunctions = [
        'concat'    => Functions\ConcatFunction::class,
        'substring' => Functions\SubstringFunction::class,
        'trim'      => Functions\TrimFunction::class,
        'lower'     => Functions\LowerFunction::class,
        'upper'     => Functions\UpperFunction::class,
        'identity'  => Functions\IdentityFunction::class,
    ];

    /**
     * @readonly Maps BUILT-IN numeric function names to AST class names.
     * @var array<string, class-string<Functions\FunctionNode>>
     */
    private static $numericFunctions = [
        'length'    => Functions\LengthFunction::class,
        'locate'    => Functions\LocateFunction::class,
        'abs'       => Functions\AbsFunction::class,
        'sqrt'      => Functions\SqrtFunction::class,
        'mod'       => Functions\ModFunction::class,
        'size'      => Functions\SizeFunction::class,
        'date_diff' => Functions\DateDiffFunction::class,
        'bit_and'   => Functions\BitAndFunction::class,
        'bit_or'    => Functions\BitOrFunction::class,

        // Aggregate functions
        'min'       => Functions\MinFunction::class,
        'max'       => Functions\MaxFunction::class,
        'avg'       => Functions\AvgFunction::class,
        'sum'       => Functions\SumFunction::class,
        'count'     => Functions\CountFunction::class,
    ];

    /**
     * @readonly Maps BUILT-IN datetime function names to AST class names.
     * @var array<string, class-string<Functions\FunctionNode>>
     */
    private static $datetimeFunctions = [
        'current_date'      => Functions\CurrentDateFunction::class,
        'current_time'      => Functions\CurrentTimeFunction::class,
        'current_timestamp' => Functions\CurrentTimestampFunction::class,
        'date_add'          => Functions\DateAddFunction::class,
        'date_sub'          => Functions\DateSubFunction::class,
    ];

    /*
     * Expressions that were encountered during parsing of identifiers and expressions
     * and still need to be validated.
     */

    /** @phpstan-var list<array{token: DqlToken|null, expression: mixed, nestingLevel: int}> */
    private $deferredIdentificationVariables = [];

    /** @phpstan-var list<array{token: DqlToken|null, expression: AST\PartialObjectExpression, nestingLevel: int}> */
    private $deferredPartialObjectExpressions = [];

    /** @phpstan-var list<array{token: DqlToken|null, expression: AST\PathExpression, nestingLevel: int}> */
    private $deferredPathExpressions = [];

    /** @phpstan-var list<array{token: DqlToken|null, expression: mixed, nestingLevel: int}> */
    private $deferredResultVariables = [];

    /** @phpstan-var list<array{token: DqlToken|null, expression: AST\NewObjectExpression, nestingLevel: int}> */
    private $deferredNewObjectExpressions = [];

    /**
     * The lexer.
     *
     * @var Lexer
     */
    private $lexer;

    /**
     * The parser result.
     *
     * @var ParserResult
     */
    private $parserResult;

    /**
     * The EntityManager.
     *
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * The Query to parse.
     *
     * @var Query
     */
    private $query;

    /**
     * Map of declared query components in the parsed query.
     *
     * @phpstan-var array<string, QueryComponent>
     */
    private $queryComponents = [];

    /**
     * Keeps the nesting level of defined ResultVariables.
     *
     * @var int
     */
    private $nestingLevel = 0;

    /**
     * Any additional custom tree walkers that modify the AST.
     *
     * @var list<class-string<TreeWalker>>
     */
    private $customTreeWalkers = [];

    /**
     * The custom last tree walker, if any, that is responsible for producing the output.
     *
     * @var class-string<SqlWalker>|null
     */
    private $customOutputWalker;

    /** @phpstan-var array<string, AST\SelectExpression> */
    private $identVariableExpressions = [];

    /**
     * Creates a new query parser object.
     *
     * @param Query $query The Query to parse.
     */
    public function __construct(Query $query)
    {
        $this->query        = $query;
        $this->em           = $query->getEntityManager();
        $this->lexer        = new Lexer((string) $query->getDQL());
        $this->parserResult = new ParserResult();
    }

    /**
     * Sets a custom tree walker that produces output.
     * This tree walker will be run last over the AST, after any other walkers.
     *
     * @param class-string<SqlWalker> $className
     *
     * @return void
     */
    public function setCustomOutputTreeWalker($className)
    {
        Deprecation::trigger(
            'doctrine/orm',
            'https://github.com/doctrine/orm/pull/11641',
            '%s is deprecated, set the output walker class with the \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER query hint instead',
            __METHOD__
        );

        $this->customOutputWalker = $className;
    }

    /**
     * Adds a custom tree walker for modifying the AST.
     *
     * @param class-string<TreeWalker> $className
     *
     * @return void
     */
    public function addCustomTreeWalker($className)
    {
        $this->customTreeWalkers[] = $className;
    }

    /**
     * Gets the lexer used by the parser.
     *
     * @return Lexer
     */
    public function getLexer()
    {
        return $this->lexer;
    }

    /**
     * Gets the ParserResult that is being filled with information during parsing.
     *
     * @return ParserResult
     */
    public function getParserResult()
    {
        return $this->parserResult;
    }

    /**
     * Gets the EntityManager used by the parser.
     *
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * Parses and builds AST for the given Query.
     *
     * @return AST\SelectStatement|AST\UpdateStatement|AST\DeleteStatement
     */
    public function getAST()
    {
        // Parse & build AST
        $AST = $this->QueryLanguage();

        // Process any deferred validations of some nodes in the AST.
        // This also allows post-processing of the AST for modification purposes.
        $this->processDeferredIdentificationVariables();

        if ($this->deferredPartialObjectExpressions) {
            $this->processDeferredPartialObjectExpressions();
        }

        if ($this->deferredPathExpressions) {
            $this->processDeferredPathExpressions();
        }

        if ($this->deferredResultVariables) {
            $this->processDeferredResultVariables();
        }

        if ($this->deferredNewObjectExpressions) {
            $this->processDeferredNewObjectExpressions($AST);
        }

        $this->processRootEntityAliasSelected();

        // TODO: Is there a way to remove this? It may impact the mixed hydration resultset a lot!
        $this->fixIdentificationVariableOrder($AST);

        return $AST;
    }

    /**
     * Attempts to match the given token with the current lookahead token.
     *
     * If they match, updates the lookahead token; otherwise raises a syntax
     * error.
     *
     * @param TokenType::T_* $token The token type.
     *
     * @return void
     *
     * @throws QueryException If the tokens don't match.
     */
    public function match($token)
    {
        $lookaheadType = $this->lexer->lookahead->type ?? null;

        // Short-circuit on first condition, usually types match
        if ($lookaheadType === $token) {
            $this->lexer->moveNext();

            return;
        }

        // If parameter is not identifier (1-99) must be exact match
        if ($token < TokenType::T_IDENTIFIER) {
            $this->syntaxError($this->lexer->getLiteral($token));
        }

        // If parameter is keyword (200+) must be exact match
        if ($token > TokenType::T_IDENTIFIER) {
            $this->syntaxError($this->lexer->getLiteral($token));
        }

        // If parameter is T_IDENTIFIER, then matches T_IDENTIFIER (100) and keywords (200+)
        if ($token === TokenType::T_IDENTIFIER && $lookaheadType < TokenType::T_IDENTIFIER) {
            $this->syntaxError($this->lexer->getLiteral($token));
        }

        $this->lexer->moveNext();
    }

    /**
     * Frees this parser, enabling it to be reused.
     *
     * @param bool $deep     Whether to clean peek and reset errors.
     * @param int  $position Position to reset.
     *
     * @return void
     */
    public function free($deep = false, $position = 0)
    {
        // WARNING! Use this method with care. It resets the scanner!
        $this->lexer->resetPosition($position);

        // Deep = true cleans peek and also any previously defined errors
        if ($deep) {
            $this->lexer->resetPeek();
        }

        $this->lexer->token     = null;
        $this->lexer->lookahead = null;
    }

    /**
     * Parses a query string.
     *
     * @return ParserResult
     */
    public function parse()
    {
        $AST = $this->getAST();

        $customWalkers = $this->query->getHint(Query::HINT_CUSTOM_TREE_WALKERS);
        if ($customWalkers !== false) {
            $this->customTreeWalkers = $customWalkers;
        }

        $customOutputWalker = $this->query->getHint(Query::HINT_CUSTOM_OUTPUT_WALKER);
        if ($customOutputWalker !== false) {
            $this->customOutputWalker = $customOutputWalker;
        }

        // Run any custom tree walkers over the AST
        if ($this->customTreeWalkers) {
            $treeWalkerChain = new TreeWalkerChain($this->query, $this->parserResult, $this->queryComponents);

            foreach ($this->customTreeWalkers as $walker) {
                $treeWalkerChain->addTreeWalker($walker);
            }

            switch (true) {
                case $AST instanceof AST\UpdateStatement:
                    $treeWalkerChain->walkUpdateStatement($AST);
                    break;

                case $AST instanceof AST\DeleteStatement:
                    $treeWalkerChain->walkDeleteStatement($AST);
                    break;

                case $AST instanceof AST\SelectStatement:
                default:
                    $treeWalkerChain->walkSelectStatement($AST);
            }

            $this->queryComponents = $treeWalkerChain->getQueryComponents();
        }

        $outputWalkerClass = $this->customOutputWalker ?: SqlOutputWalker::class;
        $outputWalker      = new $outputWalkerClass($this->query, $this->parserResult, $this->queryComponents);

        if ($outputWalker instanceof OutputWalker) {
            $finalizer = $outputWalker->getFinalizer($AST);
            $this->parserResult->setSqlFinalizer($finalizer);
        } else {
            Deprecation::trigger(
                'doctrine/orm',
                'https://github.com/doctrine/orm/pull/11188/',
                'Your output walker class %s should implement %s in order to provide a %s. This also means the output walker should not use the query firstResult/maxResult values, which should be read from the query by the SqlFinalizer only.',
                $outputWalkerClass,
                OutputWalker::class,
                SqlFinalizer::class
            );
            // @phpstan-ignore method.deprecated
            $executor = $outputWalker->getExecutor($AST);
            // @phpstan-ignore method.deprecated
            $this->parserResult->setSqlExecutor($executor);
        }

        return $this->parserResult;
    }

    /**
     * Fixes order of identification variables.
     *
     * They have to appear in the select clause in the same order as the
     * declarations (from ... x join ... y join ... z ...) appear in the query
     * as the hydration process relies on that order for proper operation.
     *
     * @param AST\SelectStatement|AST\DeleteStatement|AST\UpdateStatement $AST
     */
    private function fixIdentificationVariableOrder(AST\Node $AST): void
    {
        if (count($this->identVariableExpressions) <= 1) {
            return;
        }

        assert($AST instanceof AST\SelectStatement);

        foreach ($this->queryComponents as $dqlAlias => $qComp) {
            if (! isset($this->identVariableExpressions[$dqlAlias])) {
                continue;
            }

            $expr = $this->identVariableExpressions[$dqlAlias];
            $key  = array_search($expr, $AST->selectClause->selectExpressions, true);

            unset($AST->selectClause->selectExpressions[$key]);

            $AST->selectClause->selectExpressions[] = $expr;
        }
    }

    /**
     * Generates a new syntax error.
     *
     * @param string       $expected Expected string.
     * @param mixed[]|null $token    Got token.
     * @phpstan-param DqlToken|null $token
     *
     * @return void
     * @phpstan-return no-return
     *
     * @throws QueryException
     */
    public function syntaxError($expected = '', $token = null)
    {
        if ($token === null) {
            $token = $this->lexer->lookahead;
        }

        $tokenPos = $token->position ?? '-1';

        $message  = sprintf('line 0, col %d: Error: ', $tokenPos);
        $message .= $expected !== '' ? sprintf('Expected %s, got ', $expected) : 'Unexpected ';
        $message .= $this->lexer->lookahead === null ? 'end of string.' : sprintf("'%s'", $token->value);

        throw QueryException::syntaxError($message, QueryException::dqlError($this->query->getDQL() ?? ''));
    }

    /**
     * Generates a new semantical error.
     *
     * @param string       $message Optional message.
     * @param mixed[]|null $token   Optional token.
     * @phpstan-param DqlToken|null $token
     *
     * @return void
     * @phpstan-return no-return
     *
     * @throws QueryException
     */
    public function semanticalError($message = '', $token = null)
    {
        if ($token === null) {
            $token = $this->lexer->lookahead ?? new Token('fake token', 42, 0);
        }

        // Minimum exposed chars ahead of token
        $distance = 12;

        // Find a position of a final word to display in error string
        $dql    = $this->query->getDQL();
        $length = strlen($dql);
        $pos    = $token->position + $distance;
        $pos    = strpos($dql, ' ', $length > $pos ? $pos : $length);
        $length = $pos !== false ? $pos - $token->position : $distance;

        $tokenPos = $token->position > 0 ? $token->position : '-1';
        $tokenStr = substr($dql, $token->position, $length);

        // Building informative message
        $message = 'line 0, col ' . $tokenPos . " near '" . $tokenStr . "': Error: " . $message;

        throw QueryException::semanticalError($message, QueryException::dqlError($this->query->getDQL()));
    }

    /**
     * Peeks beyond the matched closing parenthesis and returns the first token after that one.
     *
     * @param bool $resetPeek Reset peek after finding the closing parenthesis.
     *
     * @return mixed[]
     * @phpstan-return DqlToken|null
     */
    private function peekBeyondClosingParenthesis(bool $resetPeek = true)
    {
        $token        = $this->lexer->peek();
        $numUnmatched = 1;

        while ($numUnmatched > 0 && $token !== null) {
            switch ($token->type) {
                case TokenType::T_OPEN_PARENTHESIS:
                    ++$numUnmatched;
                    break;

                case TokenType::T_CLOSE_PARENTHESIS:
                    --$numUnmatched;
                    break;

                default:
                    // Do nothing
            }

            $token = $this->lexer->peek();
        }

        if ($resetPeek) {
            $this->lexer->resetPeek();
        }

        return $token;
    }

    /**
     * Checks if the given token indicates a mathematical operator.
     *
     * @phpstan-param DqlToken|null $token
     */
    private function isMathOperator($token): bool
    {
        return $token !== null && in_array($token->type, [TokenType::T_PLUS, TokenType::T_MINUS, TokenType::T_DIVIDE, TokenType::T_MULTIPLY], true);
    }

    /**
     * Checks if the next-next (after lookahead) token starts a function.
     *
     * @return bool TRUE if the next-next tokens start a function, FALSE otherwise.
     */
    private function isFunction(): bool
    {
        assert($this->lexer->lookahead !== null);
        $lookaheadType = $this->lexer->lookahead->type;
        $peek          = $this->lexer->peek();

        $this->lexer->resetPeek();

        return $lookaheadType >= TokenType::T_IDENTIFIER && $peek !== null && $peek->type === TokenType::T_OPEN_PARENTHESIS;
    }

    /**
     * Checks whether the given token type indicates an aggregate function.
     *
     * @phpstan-param TokenType::T_* $tokenType
     *
     * @return bool TRUE if the token type is an aggregate function, FALSE otherwise.
     */
    private function isAggregateFunction(int $tokenType): bool
    {
        return in_array(
            $tokenType,
            [TokenType::T_AVG, TokenType::T_MIN, TokenType::T_MAX, TokenType::T_SUM, TokenType::T_COUNT],
            true
        );
    }

    /**
     * Checks whether the current lookahead token of the lexer has the type T_ALL, T_ANY or T_SOME.
     */
    private function isNextAllAnySome(): bool
    {
        assert($this->lexer->lookahead !== null);

        return in_array(
            $this->lexer->lookahead->type,
            [TokenType::T_ALL, TokenType::T_ANY, TokenType::T_SOME],
            true
        );
    }

    /**
     * Validates that the given <tt>IdentificationVariable</tt> is semantically correct.
     * It must exist in query components list.
     */
    private function processDeferredIdentificationVariables(): void
    {
        foreach ($this->deferredIdentificationVariables as $deferredItem) {
            $identVariable = $deferredItem['expression'];

            // Check if IdentificationVariable exists in queryComponents
            if (! isset($this->queryComponents[$identVariable])) {
                $this->semanticalError(
                    sprintf("'%s' is not defined.", $identVariable),
                    $deferredItem['token']
                );
            }

            $qComp = $this->queryComponents[$identVariable];

            // Check if queryComponent points to an AbstractSchemaName or a ResultVariable
            if (! isset($qComp['metadata'])) {
                $this->semanticalError(
                    sprintf("'%s' does not point to a Class.", $identVariable),
                    $deferredItem['token']
                );
            }

            // Validate if identification variable nesting level is lower or equal than the current one
            if ($qComp['nestingLevel'] > $deferredItem['nestingLevel']) {
                $this->semanticalError(
                    sprintf("'%s' is used outside the scope of its declaration.", $identVariable),
                    $deferredItem['token']
                );
            }
        }
    }

    /**
     * Validates that the given <tt>NewObjectExpression</tt>.
     */
    private function processDeferredNewObjectExpressions(AST\SelectStatement $AST): void
    {
        foreach ($this->deferredNewObjectExpressions as $deferredItem) {
            $expression    = $deferredItem['expression'];
            $token         = $deferredItem['token'];
            $className     = $expression->className;
            $args          = $expression->args;
            $fromClassName = $AST->fromClause->identificationVariableDeclarations[0]->rangeVariableDeclaration->abstractSchemaName ?? null;

            // If the namespace is not given then assumes the first FROM entity namespace
            if (! str_contains($className, '\\') && ! class_exists($className) && is_string($fromClassName) && str_contains($fromClassName, '\\')) {
                $namespace = substr($fromClassName, 0, strrpos($fromClassName, '\\'));
                $fqcn      = $namespace . '\\' . $className;

                if (class_exists($fqcn)) {
                    $expression->className = $fqcn;
                    $className             = $fqcn;
                }
            }

            if (! class_exists($className)) {
                $this->semanticalError(sprintf('Class "%s" is not defined.', $className), $token);
            }

            $class = new ReflectionClass($className);

            if (! $class->isInstantiable()) {
                $this->semanticalError(sprintf('Class "%s" can not be instantiated.', $className), $token);
            }

            if ($class->getConstructor() === null) {
                $this->semanticalError(sprintf('Class "%s" has not a valid constructor.', $className), $token);
            }

            if ($class->getConstructor()->getNumberOfRequiredParameters() > count($args)) {
                $this->semanticalError(sprintf('Number of arguments does not match with "%s" constructor declaration.', $className), $token);
            }
        }
    }

    /**
     * Validates that the given <tt>PartialObjectExpression</tt> is semantically correct.
     * It must exist in query components list.
     */
    private function processDeferredPartialObjectExpressions(): void
    {
        foreach ($this->deferredPartialObjectExpressions as $deferredItem) {
            $expr  = $deferredItem['expression'];
            $class = $this->getMetadataForDqlAlias($expr->identificationVariable);

            foreach ($expr->partialFieldSet as $field) {
                if (isset($class->fieldMappings[$field])) {
                    continue;
                }

                if (
                    isset($class->associationMappings[$field]) &&
                    $class->associationMappings[$field]['isOwningSide'] &&
                    $class->associationMappings[$field]['type'] & ClassMetadata::TO_ONE
                ) {
                    continue;
                }

                $this->semanticalError(sprintf(
                    "There is no mapped field named '%s' on class %s.",
                    $field,
                    $class->name
                ), $deferredItem['token']);
            }

            if (array_intersect($class->identifier, $expr->partialFieldSet) !== $class->identifier) {
                $this->semanticalError(
                    'The partial field selection of class ' . $class->name . ' must contain the identifier.',
                    $deferredItem['token']
                );
            }
        }
    }

    /**
     * Validates that the given <tt>ResultVariable</tt> is semantically correct.
     * It must exist in query components list.
     */
    private function processDeferredResultVariables(): void
    {
        foreach ($this->deferredResultVariables as $deferredItem) {
            $resultVariable = $deferredItem['expression'];

            // Check if ResultVariable exists in queryComponents
            if (! isset($this->queryComponents[$resultVariable])) {
                $this->semanticalError(
                    sprintf("'%s' is not defined.", $resultVariable),
                    $deferredItem['token']
                );
            }

            $qComp = $this->queryComponents[$resultVariable];

            // Check if queryComponent points to an AbstractSchemaName or a ResultVariable
            if (! isset($qComp['resultVariable'])) {
                $this->semanticalError(
                    sprintf("'%s' does not point to a ResultVariable.", $resultVariable),
                    $deferredItem['token']
                );
            }

            // Validate if identification variable nesting level is lower or equal than the current one
            if ($qComp['nestingLevel'] > $deferredItem['nestingLevel']) {
                $this->semanticalError(
                    sprintf("'%s' is used outside the scope of its declaration.", $resultVariable),
                    $deferredItem['token']
                );
            }
        }
    }

    /**
     * Validates that the given <tt>PathExpression</tt> is semantically correct for grammar rules:
     *
     * AssociationPathExpression             ::= CollectionValuedPathExpression | SingleValuedAssociationPathExpression
     * SingleValuedPathExpression            ::= StateFieldPathExpression | SingleValuedAssociationPathExpression
     * StateFieldPathExpression              ::= IdentificationVariable "." StateField
     * SingleValuedAssociationPathExpression ::= IdentificationVariable "." SingleValuedAssociationField
     * CollectionValuedPathExpression        ::= IdentificationVariable "." CollectionValuedAssociationField
     */
    private function processDeferredPathExpressions(): void
    {
        foreach ($this->deferredPathExpressions as $deferredItem) {
            $pathExpression = $deferredItem['expression'];

            $class = $this->getMetadataForDqlAlias($pathExpression->identificationVariable);

            $field = $pathExpression->field;
            if ($field === null) {
                $field = $pathExpression->field = $class->identifier[0];
            }

            // Check if field or association exists
            if (! isset($class->associationMappings[$field]) && ! isset($class->fieldMappings[$field])) {
                $this->semanticalError(
                    'Class ' . $class->name . ' has no field or association named ' . $field,
                    $deferredItem['token']
                );
            }

            $fieldType = AST\PathExpression::TYPE_STATE_FIELD;

            if (isset($class->associationMappings[$field])) {
                $assoc = $class->associationMappings[$field];

                $fieldType = $assoc['type'] & ClassMetadata::TO_ONE
                    ? AST\PathExpression::TYPE_SINGLE_VALUED_ASSOCIATION
                    : AST\PathExpression::TYPE_COLLECTION_VALUED_ASSOCIATION;
            }

            // Validate if PathExpression is one of the expected types
            $expectedType = $pathExpression->expectedType;

            if (! ($expectedType & $fieldType)) {
                // We need to recognize which was expected type(s)
                $expectedStringTypes = [];

                // Validate state field type
                if ($expectedType & AST\PathExpression::TYPE_STATE_FIELD) {
                    $expectedStringTypes[] = 'StateFieldPathExpression';
                }

                // Validate single valued association (*-to-one)
                if ($expectedType & AST\PathExpression::TYPE_SINGLE_VALUED_ASSOCIATION) {
                    $expectedStringTypes[] = 'SingleValuedAssociationField';
                }

                // Validate single valued association (*-to-many)
                if ($expectedType & AST\PathExpression::TYPE_COLLECTION_VALUED_ASSOCIATION) {
                    $expectedStringTypes[] = 'CollectionValuedAssociationField';
                }

                // Build the error message
                $semanticalError  = 'Invalid PathExpression. ';
                $semanticalError .= count($expectedStringTypes) === 1
                    ? 'Must be a ' . $expectedStringTypes[0] . '.'
                    : implode(' or ', $expectedStringTypes) . ' expected.';

                $this->semanticalError($semanticalError, $deferredItem['token']);
            }

            // We need to force the type in PathExpression
            $pathExpression->type = $fieldType;
        }
    }

    private function processRootEntityAliasSelected(): void
    {
        if (! count($this->identVariableExpressions)) {
            return;
        }

        foreach ($this->identVariableExpressions as $dqlAlias => $expr) {
            if (isset($this->queryComponents[$dqlAlias]) && ! isset($this->queryComponents[$dqlAlias]['parent'])) {
                return;
            }
        }

        $this->semanticalError('Cannot select entity through identification variables without choosing at least one root entity alias.');
    }

    /**
     * QueryLanguage ::= SelectStatement | UpdateStatement | DeleteStatement
     *
     * @return AST\SelectStatement|AST\UpdateStatement|AST\DeleteStatement
     */
    public function QueryLanguage()
    {
        $statement = null;

        $this->lexer->moveNext();

        switch ($this->lexer->lookahead->type ?? null) {
            case TokenType::T_SELECT:
                $statement = $this->SelectStatement();
                break;

            case TokenType::T_UPDATE:
                $statement = $this->UpdateStatement();
                break;

            case TokenType::T_DELETE:
                $statement = $this->DeleteStatement();
                break;

            default:
                $this->syntaxError('SELECT, UPDATE or DELETE');
                break;
        }

        // Check for end of string
        if ($this->lexer->lookahead !== null) {
            $this->syntaxError('end of string');
        }

        return $statement;
    }

    /**
     * SelectStatement ::= SelectClause FromClause [WhereClause] [GroupByClause] [HavingClause] [OrderByClause]
     *
     * @return AST\SelectStatement
     */
    public function SelectStatement()
    {
        $selectStatement = new AST\SelectStatement($this->SelectClause(), $this->FromClause());

        $selectStatement->whereClause   = $this->lexer->isNextToken(TokenType::T_WHERE) ? $this->WhereClause() : null;
        $selectStatement->groupByClause = $this->lexer->isNextToken(TokenType::T_GROUP) ? $this->GroupByClause() : null;
        $selectStatement->havingClause  = $this->lexer->isNextToken(TokenType::T_HAVING) ? $this->HavingClause() : null;
        $selectStatement->orderByClause = $this->lexer->isNextToken(TokenType::T_ORDER) ? $this->OrderByClause() : null;

        return $selectStatement;
    }

    /**
     * UpdateStatement ::= UpdateClause [WhereClause]
     *
     * @return AST\UpdateStatement
     */
    public function UpdateStatement()
    {
        $updateStatement = new AST\UpdateStatement($this->UpdateClause());

        $updateStatement->whereClause = $this->lexer->isNextToken(TokenType::T_WHERE) ? $this->WhereClause() : null;

        return $updateStatement;
    }

    /**
     * DeleteStatement ::= DeleteClause [WhereClause]
     *
     * @return AST\DeleteStatement
     */
    public function DeleteStatement()
    {
        $deleteStatement = new AST\DeleteStatement($this->DeleteClause());

        $deleteStatement->whereClause = $this->lexer->isNextToken(TokenType::T_WHERE) ? $this->WhereClause() : null;

        return $deleteStatement;
    }

    /**
     * IdentificationVariable ::= identifier
     *
     * @return string
     */
    public function IdentificationVariable()
    {
        $this->match(TokenType::T_IDENTIFIER);

        assert($this->lexer->token !== null);
        $identVariable = $this->lexer->token->value;

        $this->deferredIdentificationVariables[] = [
            'expression'   => $identVariable,
            'nestingLevel' => $this->nestingLevel,
            'token'        => $this->lexer->token,
        ];

        return $identVariable;
    }

    /**
     * AliasIdentificationVariable = identifier
     *
     * @return string
     */
    public function AliasIdentificationVariable()
    {
        $this->match(TokenType::T_IDENTIFIER);

        assert($this->lexer->token !== null);
        $aliasIdentVariable = $this->lexer->token->value;
        $exists             = isset($this->queryComponents[$aliasIdentVariable]);

        if ($exists) {
            $this->semanticalError(
                sprintf("'%s' is already defined.", $aliasIdentVariable),
                $this->lexer->token
            );
        }

        return $aliasIdentVariable;
    }

    /**
     * AbstractSchemaName ::= fully_qualified_name | aliased_name | identifier
     *
     * @return string
     */
    public function AbstractSchemaName()
    {
        if ($this->lexer->isNextToken(TokenType::T_FULLY_QUALIFIED_NAME)) {
            $this->match(TokenType::T_FULLY_QUALIFIED_NAME);
            assert($this->lexer->token !== null);

            return $this->lexer->token->value;
        }

        if ($this->lexer->isNextToken(TokenType::T_IDENTIFIER)) {
            $this->match(TokenType::T_IDENTIFIER);
            assert($this->lexer->token !== null);

            return $this->lexer->token->value;
        }

        // @phpstan-ignore classConstant.deprecated
        $this->match(TokenType::T_ALIASED_NAME);

        assert($this->lexer->token !== null);

        Deprecation::trigger(
            'doctrine/orm',
            'https://github.com/doctrine/orm/issues/8818',
            'Short namespace aliases such as "%s" are deprecated and will be removed in Doctrine ORM 3.0.',
            $this->lexer->token->value
        );

        [$namespaceAlias, $simpleClassName] = explode(':', $this->lexer->token->value);

        return $this->em->getConfiguration()->getEntityNamespace($namespaceAlias) . '\\' . $simpleClassName;
    }

    /**
     * Validates an AbstractSchemaName, making sure the class exists.
     *
     * @param string $schemaName The name to validate.
     *
     * @throws QueryException if the name does not exist.
     */
    private function validateAbstractSchemaName(string $schemaName): void
    {
        assert($this->lexer->token !== null);
        if (! (class_exists($schemaName, true) || interface_exists($schemaName, true))) {
            $this->semanticalError(
                sprintf("Class '%s' is not defined.", $schemaName),
                $this->lexer->token
            );
        }
    }

    /**
     * AliasResultVariable ::= identifier
     *
     * @return string
     */
    public function AliasResultVariable()
    {
        $this->match(TokenType::T_IDENTIFIER);

        assert($this->lexer->token !== null);
        $resultVariable = $this->lexer->token->value;
        $exists         = isset($this->queryComponents[$resultVariable]);

        if ($exists) {
            $this->semanticalError(
                sprintf("'%s' is already defined.", $resultVariable),
                $this->lexer->token
            );
        }

        return $resultVariable;
    }

    /**
     * ResultVariable ::= identifier
     *
     * @return string
     */
    public function ResultVariable()
    {
        $this->match(TokenType::T_IDENTIFIER);

        assert($this->lexer->token !== null);
        $resultVariable = $this->lexer->token->value;

        // Defer ResultVariable validation
        $this->deferredResultVariables[] = [
            'expression'   => $resultVariable,
            'nestingLevel' => $this->nestingLevel,
            'token'        => $this->lexer->token,
        ];

        return $resultVariable;
    }

    /**
     * JoinAssociationPathExpression ::= IdentificationVariable "." (CollectionValuedAssociationField | SingleValuedAssociationField)
     *
     * @return AST\JoinAssociationPathExpression
     */
    public function JoinAssociationPathExpression()
    {
        $identVariable = $this->IdentificationVariable();

        if (! isset($this->queryComponents[$identVariable])) {
            $this->semanticalError(
                'Identification Variable ' . $identVariable . ' used in join path expression but was not defined before.'
            );
        }

        $this->match(TokenType::T_DOT);
        $this->match(TokenType::T_IDENTIFIER);

        assert($this->lexer->token !== null);
        $field = $this->lexer->token->value;

        // Validate association field
        $class = $this->getMetadataForDqlAlias($identVariable);

        if (! $class->hasAssociation($field)) {
            $this->semanticalError('Class ' . $class->name . ' has no association named ' . $field);
        }

        return new AST\JoinAssociationPathExpression($identVariable, $field);
    }

    /**
     * Parses an arbitrary path expression and defers semantical validation
     * based on expected types.
     *
     * PathExpression ::= IdentificationVariable {"." identifier}*
     *
     * @param int $expectedTypes
     * @phpstan-param int-mask-of<AST\PathExpression::TYPE_*> $expectedTypes
     *
     * @return AST\PathExpression
     */
    public function PathExpression($expectedTypes)
    {
        $identVariable = $this->IdentificationVariable();
        $field         = null;

        assert($this->lexer->token !== null);
        if ($this->lexer->isNextToken(TokenType::T_DOT)) {
            $this->match(TokenType::T_DOT);
            $this->match(TokenType::T_IDENTIFIER);

            $field = $this->lexer->token->value;

            while ($this->lexer->isNextToken(TokenType::T_DOT)) {
                $this->match(TokenType::T_DOT);
                $this->match(TokenType::T_IDENTIFIER);
                $field .= '.' . $this->lexer->token->value;
            }
        }

        // Creating AST node
        $pathExpr = new AST\PathExpression($expectedTypes, $identVariable, $field);

        // Defer PathExpression validation if requested to be deferred
        $this->deferredPathExpressions[] = [
            'expression'   => $pathExpr,
            'nestingLevel' => $this->nestingLevel,
            'token'        => $this->lexer->token,
        ];

        return $pathExpr;
    }

    /**
     * AssociationPathExpression ::= CollectionValuedPathExpression | SingleValuedAssociationPathExpression
     *
     * @return AST\PathExpression
     */
    public function AssociationPathExpression()
    {
        return $this->PathExpression(
            AST\PathExpression::TYPE_SINGLE_VALUED_ASSOCIATION |
            AST\PathExpression::TYPE_COLLECTION_VALUED_ASSOCIATION
        );
    }

    /**
     * SingleValuedPathExpression ::= StateFieldPathExpression | SingleValuedAssociationPathExpression
     *
     * @return AST\PathExpression
     */
    public function SingleValuedPathExpression()
    {
        return $this->PathExpression(
            AST\PathExpression::TYPE_STATE_FIELD |
            AST\PathExpression::TYPE_SINGLE_VALUED_ASSOCIATION
        );
    }

    /**
     * StateFieldPathExpression ::= IdentificationVariable "." StateField
     *
     * @return AST\PathExpression
     */
    public function StateFieldPathExpression()
    {
        return $this->PathExpression(AST\PathExpression::TYPE_STATE_FIELD);
    }

    /**
     * SingleValuedAssociationPathExpression ::= IdentificationVariable "." SingleValuedAssociationField
     *
     * @return AST\PathExpression
     */
    public function SingleValuedAssociationPathExpression()
    {
        return $this->PathExpression(AST\PathExpression::TYPE_SINGLE_VALUED_ASSOCIATION);
    }

    /**
     * CollectionValuedPathExpression ::= IdentificationVariable "." CollectionValuedAssociationField
     *
     * @return AST\PathExpression
     */
    public function CollectionValuedPathExpression()
    {
        return $this->PathExpression(AST\PathExpression::TYPE_COLLECTION_VALUED_ASSOCIATION);
    }

    /**
     * SelectClause ::= "SELECT" ["DISTINCT"] SelectExpression {"," SelectExpression}
     *
     * @return AST\SelectClause
     */
    public function SelectClause()
    {
        $isDistinct = false;
        $this->match(TokenType::T_SELECT);

        // Check for DISTINCT
        if ($this->lexer->isNextToken(TokenType::T_DISTINCT)) {
            $this->match(TokenType::T_DISTINCT);

            $isDistinct = true;
        }

        // Process SelectExpressions (1..N)
        $selectExpressions   = [];
        $selectExpressions[] = $this->SelectExpression();

        while ($this->lexer->isNextToken(TokenType::T_COMMA)) {
            $this->match(TokenType::T_COMMA);

            $selectExpressions[] = $this->SelectExpression();
        }

        return new AST\SelectClause($selectExpressions, $isDistinct);
    }

    /**
     * SimpleSelectClause ::= "SELECT" ["DISTINCT"] SimpleSelectExpression
     *
     * @return AST\SimpleSelectClause
     */
    public function SimpleSelectClause()
    {
        $isDistinct = false;
        $this->match(TokenType::T_SELECT);

        if ($this->lexer->isNextToken(TokenType::T_DISTINCT)) {
            $this->match(TokenType::T_DISTINCT);

            $isDistinct = true;
        }

        return new AST\SimpleSelectClause($this->SimpleSelectExpression(), $isDistinct);
    }

    /**
     * UpdateClause ::= "UPDATE" AbstractSchemaName ["AS"] AliasIdentificationVariable "SET" UpdateItem {"," UpdateItem}*
     *
     * @return AST\UpdateClause
     */
    public function UpdateClause()
    {
        $this->match(TokenType::T_UPDATE);
        assert($this->lexer->lookahead !== null);

        $token              = $this->lexer->lookahead;
        $abstractSchemaName = $this->AbstractSchemaName();

        $this->validateAbstractSchemaName($abstractSchemaName);

        if ($this->lexer->isNextToken(TokenType::T_AS)) {
            $this->match(TokenType::T_AS);
        }

        $aliasIdentificationVariable = $this->AliasIdentificationVariable();

        $class = $this->em->getClassMetadata($abstractSchemaName);

        // Building queryComponent
        $queryComponent = [
            'metadata'     => $class,
            'parent'       => null,
            'relation'     => null,
            'map'          => null,
            'nestingLevel' => $this->nestingLevel,
            'token'        => $token,
        ];

        $this->queryComponents[$aliasIdentificationVariable] = $queryComponent;

        $this->match(TokenType::T_SET);

        $updateItems   = [];
        $updateItems[] = $this->UpdateItem();

        while ($this->lexer->isNextToken(TokenType::T_COMMA)) {
            $this->match(TokenType::T_COMMA);

            $updateItems[] = $this->UpdateItem();
        }

        $updateClause                              = new AST\UpdateClause($abstractSchemaName, $updateItems);
        $updateClause->aliasIdentificationVariable = $aliasIdentificationVariable;

        return $updateClause;
    }

    /**
     * DeleteClause ::= "DELETE" ["FROM"] AbstractSchemaName ["AS"] AliasIdentificationVariable
     *
     * @return AST\DeleteClause
     */
    public function DeleteClause()
    {
        $this->match(TokenType::T_DELETE);

        if ($this->lexer->isNextToken(TokenType::T_FROM)) {
            $this->match(TokenType::T_FROM);
        }

        assert($this->lexer->lookahead !== null);
        $token              = $this->lexer->lookahead;
        $abstractSchemaName = $this->AbstractSchemaName();

        $this->validateAbstractSchemaName($abstractSchemaName);

        $deleteClause = new AST\DeleteClause($abstractSchemaName);

        if ($this->lexer->isNextToken(TokenType::T_AS)) {
            $this->match(TokenType::T_AS);
        }

        $aliasIdentificationVariable = $this->lexer->isNextToken(TokenType::T_IDENTIFIER)
            ? $this->AliasIdentificationVariable()
            : 'alias_should_have_been_set';

        $deleteClause->aliasIdentificationVariable = $aliasIdentificationVariable;
        $class                                     = $this->em->getClassMetadata($deleteClause->abstractSchemaName);

        // Building queryComponent
        $queryComponent = [
            'metadata'     => $class,
            'parent'       => null,
            'relation'     => null,
            'map'          => null,
            'nestingLevel' => $this->nestingLevel,
            'token'        => $token,
        ];

        $this->queryComponents[$aliasIdentificationVariable] = $queryComponent;

        return $deleteClause;
    }

    /**
     * FromClause ::= "FROM" IdentificationVariableDeclaration {"," IdentificationVariableDeclaration}*
     *
     * @return AST\FromClause
     */
    public function FromClause()
    {
        $this->match(TokenType::T_FROM);

        $identificationVariableDeclarations   = [];
        $identificationVariableDeclarations[] = $this->IdentificationVariableDeclaration();

        while ($this->lexer->isNextToken(TokenType::T_COMMA)) {
            $this->match(TokenType::T_COMMA);

            $identificationVariableDeclarations[] = $this->IdentificationVariableDeclaration();
        }

        return new AST\FromClause($identificationVariableDeclarations);
    }

    /**
     * SubselectFromClause ::= "FROM" SubselectIdentificationVariableDeclaration {"," SubselectIdentificationVariableDeclaration}*
     *
     * @return AST\SubselectFromClause
     */
    public function SubselectFromClause()
    {
        $this->match(TokenType::T_FROM);

        $identificationVariables   = [];
        $identificationVariables[] = $this->SubselectIdentificationVariableDeclaration();

        while ($this->lexer->isNextToken(TokenType::T_COMMA)) {
            $this->match(TokenType::T_COMMA);

            $identificationVariables[] = $this->SubselectIdentificationVariableDeclaration();
        }

        return new AST\SubselectFromClause($identificationVariables);
    }

    /**
     * WhereClause ::= "WHERE" ConditionalExpression
     *
     * @return AST\WhereClause
     */
    public function WhereClause()
    {
        $this->match(TokenType::T_WHERE);

        return new AST\WhereClause($this->ConditionalExpression());
    }

    /**
     * HavingClause ::= "HAVING" ConditionalExpression
     *
     * @return AST\HavingClause
     */
    public function HavingClause()
    {
        $this->match(TokenType::T_HAVING);

        return new AST\HavingClause($this->ConditionalExpression());
    }

    /**
     * GroupByClause ::= "GROUP" "BY" GroupByItem {"," GroupByItem}*
     *
     * @return AST\GroupByClause
     */
    public function GroupByClause()
    {
        $this->match(TokenType::T_GROUP);
        $this->match(TokenType::T_BY);

        $groupByItems = [$this->GroupByItem()];

        while ($this->lexer->isNextToken(TokenType::T_COMMA)) {
            $this->match(TokenType::T_COMMA);

            $groupByItems[] = $this->GroupByItem();
        }

        return new AST\GroupByClause($groupByItems);
    }

    /**
     * OrderByClause ::= "ORDER" "BY" OrderByItem {"," OrderByItem}*
     *
     * @return AST\OrderByClause
     */
    public function OrderByClause()
    {
        $this->match(TokenType::T_ORDER);
        $this->match(TokenType::T_BY);

        $orderByItems   = [];
        $orderByItems[] = $this->OrderByItem();

        while ($this->lexer->isNextToken(TokenType::T_COMMA)) {
            $this->match(TokenType::T_COMMA);

            $orderByItems[] = $this->OrderByItem();
        }

        return new AST\OrderByClause($orderByItems);
    }

    /**
     * Subselect ::= SimpleSelectClause SubselectFromClause [WhereClause] [GroupByClause] [HavingClause] [OrderByClause]
     *
     * @return AST\Subselect
     */
    public function Subselect()
    {
        // Increase query nesting level
        $this->nestingLevel++;

        $subselect = new AST\Subselect($this->SimpleSelectClause(), $this->SubselectFromClause());

        $subselect->whereClause   = $this->lexer->isNextToken(TokenType::T_WHERE) ? $this->WhereClause() : null;
        $subselect->groupByClause = $this->lexer->isNextToken(TokenType::T_GROUP) ? $this->GroupByClause() : null;
        $subselect->havingClause  = $this->lexer->isNextToken(TokenType::T_HAVING) ? $this->HavingClause() : null;
        $subselect->orderByClause = $this->lexer->isNextToken(TokenType::T_ORDER) ? $this->OrderByClause() : null;

        // Decrease query nesting level
        $this->nestingLevel--;

        return $subselect;
    }

    /**
     * UpdateItem ::= SingleValuedPathExpression "=" NewValue
     *
     * @return AST\UpdateItem
     */
    public function UpdateItem()
    {
        $pathExpr = $this->SingleValuedPathExpression();

        $this->match(TokenType::T_EQUALS);

        return new AST\UpdateItem($pathExpr, $this->NewValue());
    }

    /**
     * GroupByItem ::= IdentificationVariable | ResultVariable | SingleValuedPathExpression
     *
     * @return string|AST\PathExpression
     */
    public function GroupByItem()
    {
        // We need to check if we are in a IdentificationVariable or SingleValuedPathExpression
        $glimpse = $this->lexer->glimpse();

        if ($glimpse !== null && $glimpse->type === TokenType::T_DOT) {
            return $this->SingleValuedPathExpression();
        }

        assert($this->lexer->lookahead !== null);
        // Still need to decide between IdentificationVariable or ResultVariable
        $lookaheadValue = $this->lexer->lookahead->value;

        if (! isset($this->queryComponents[$lookaheadValue])) {
            $this->semanticalError('Cannot group by undefined identification or result variable.');
        }

        return isset($this->queryComponents[$lookaheadValue]['metadata'])
            ? $this->IdentificationVariable()
            : $this->ResultVariable();
    }

    /**
     * OrderByItem ::= (
     *      SimpleArithmeticExpression | SingleValuedPathExpression | CaseExpression |
     *      ScalarExpression | ResultVariable | FunctionDeclaration
     * ) ["ASC" | "DESC"]
     *
     * @return AST\OrderByItem
     */
    public function OrderByItem()
    {
        $this->lexer->peek(); // lookahead => '.'
        $this->lexer->peek(); // lookahead => token after '.'

        $peek = $this->lexer->peek(); // lookahead => token after the token after the '.'

        $this->lexer->resetPeek();

        $glimpse = $this->lexer->glimpse();

        assert($this->lexer->lookahead !== null);
        switch (true) {
            case $this->isMathOperator($peek) || $this->isMathOperator($glimpse):
                $expr = $this->SimpleArithmeticExpression();
                break;

            case $glimpse !== null && $glimpse->type === TokenType::T_DOT:
                $expr = $this->SingleValuedPathExpression();
                break;

            case $this->lexer->peek() && $this->isMathOperator($this->peekBeyondClosingParenthesis()):
                $expr = $this->ScalarExpression();
                break;

            case $this->lexer->lookahead->type === TokenType::T_CASE:
                $expr = $this->CaseExpression();
                break;

            case $this->isFunction():
                $expr = $this->FunctionDeclaration();
                break;

            default:
                $expr = $this->ResultVariable();
                break;
        }

        $type = 'ASC';
        $item = new AST\OrderByItem($expr);

        switch (true) {
            case $this->lexer->isNextToken(TokenType::T_DESC):
                $this->match(TokenType::T_DESC);
                $type = 'DESC';
                break;

            case $this->lexer->isNextToken(TokenType::T_ASC):
                $this->match(TokenType::T_ASC);
                break;

            default:
                // Do nothing
        }

        $item->type = $type;

        return $item;
    }

    /**
     * NewValue ::= SimpleArithmeticExpression | StringPrimary | DatetimePrimary | BooleanPrimary |
     *      EnumPrimary | SimpleEntityExpression | "NULL"
     *
     * NOTE: Since it is not possible to correctly recognize individual types, here is the full
     * grammar that needs to be supported:
     *
     * NewValue ::= SimpleArithmeticExpression | "NULL"
     *
     * SimpleArithmeticExpression covers all *Primary grammar rules and also SimpleEntityExpression
     *
     * @return AST\ArithmeticExpression|AST\InputParameter|null
     */
    public function NewValue()
    {
        if ($this->lexer->isNextToken(TokenType::T_NULL)) {
            $this->match(TokenType::T_NULL);

            return null;
        }

        if ($this->lexer->isNextToken(TokenType::T_INPUT_PARAMETER)) {
            $this->match(TokenType::T_INPUT_PARAMETER);
            assert($this->lexer->token !== null);

            return new AST\InputParameter($this->lexer->token->value);
        }

        return $this->ArithmeticExpression();
    }

    /**
     * IdentificationVariableDeclaration ::= RangeVariableDeclaration [IndexBy] {Join}*
     *
     * @return AST\IdentificationVariableDeclaration
     */
    public function IdentificationVariableDeclaration()
    {
        $joins                    = [];
        $rangeVariableDeclaration = $this->RangeVariableDeclaration();
        $indexBy                  = $this->lexer->isNextToken(TokenType::T_INDEX)
            ? $this->IndexBy()
            : null;

        $rangeVariableDeclaration->isRoot = true;

        while (
            $this->lexer->isNextToken(TokenType::T_LEFT) ||
            $this->lexer->isNextToken(TokenType::T_INNER) ||
            $this->lexer->isNextToken(TokenType::T_JOIN)
        ) {
            $joins[] = $this->Join();
        }

        return new AST\IdentificationVariableDeclaration(
            $rangeVariableDeclaration,
            $indexBy,
            $joins
        );
    }

    /**
     * SubselectIdentificationVariableDeclaration ::= IdentificationVariableDeclaration
     *
     * {Internal note: WARNING: Solution is harder than a bare implementation.
     * Desired EBNF support:
     *
     * SubselectIdentificationVariableDeclaration ::= IdentificationVariableDeclaration | (AssociationPathExpression ["AS"] AliasIdentificationVariable)
     *
     * It demands that entire SQL generation to become programmatical. This is
     * needed because association based subselect requires "WHERE" conditional
     * expressions to be injected, but there is no scope to do that. Only scope
     * accessible is "FROM", prohibiting an easy implementation without larger
     * changes.}
     *
     * @return AST\IdentificationVariableDeclaration
     */
    public function SubselectIdentificationVariableDeclaration()
    {
        /*
        NOT YET IMPLEMENTED!

        $glimpse = $this->lexer->glimpse();

        if ($glimpse->type == TokenType::T_DOT) {
            $associationPathExpression = $this->AssociationPathExpression();

            if ($this->lexer->isNextToken(TokenType::T_AS)) {
                $this->match(TokenType::T_AS);
            }

            $aliasIdentificationVariable = $this->AliasIdentificationVariable();
            $identificationVariable      = $associationPathExpression->identificationVariable;
            $field                       = $associationPathExpression->associationField;

            $class       = $this->queryComponents[$identificationVariable]['metadata'];
            $targetClass = $this->em->getClassMetadata($class->associationMappings[$field]['targetEntity']);

            // Building queryComponent
            $joinQueryComponent = array(
                'metadata'     => $targetClass,
                'parent'       => $identificationVariable,
                'relation'     => $class->getAssociationMapping($field),
                'map'          => null,
                'nestingLevel' => $this->nestingLevel,
                'token'        => $this->lexer->lookahead
            );

            $this->queryComponents[$aliasIdentificationVariable] = $joinQueryComponent;

            return new AST\SubselectIdentificationVariableDeclaration(
                $associationPathExpression, $aliasIdentificationVariable
            );
        }
        */

        return $this->IdentificationVariableDeclaration();
    }

    /**
     * Join ::= ["LEFT" ["OUTER"] | "INNER"] "JOIN"
     *          (JoinAssociationDeclaration | RangeVariableDeclaration)
     *          ["WITH" ConditionalExpression]
     *
     * @return AST\Join
     */
    public function Join()
    {
        // Check Join type
        $joinType = AST\Join::JOIN_TYPE_INNER;

        switch (true) {
            case $this->lexer->isNextToken(TokenType::T_LEFT):
                $this->match(TokenType::T_LEFT);

                $joinType = AST\Join::JOIN_TYPE_LEFT;

                // Possible LEFT OUTER join
                if ($this->lexer->isNextToken(TokenType::T_OUTER)) {
                    $this->match(TokenType::T_OUTER);

                    $joinType = AST\Join::JOIN_TYPE_LEFTOUTER;
                }

                break;

            case $this->lexer->isNextToken(TokenType::T_INNER):
                $this->match(TokenType::T_INNER);
                break;

            default:
                // Do nothing
        }

        $this->match(TokenType::T_JOIN);

        $next = $this->lexer->glimpse();
        assert($next !== null);
        $joinDeclaration = $next->type === TokenType::T_DOT ? $this->JoinAssociationDeclaration() : $this->RangeVariableDeclaration();
        $adhocConditions = $this->lexer->isNextToken(TokenType::T_WITH);
        $join            = new AST\Join($joinType, $joinDeclaration);

        // Describe non-root join declaration
        if ($joinDeclaration instanceof AST\RangeVariableDeclaration) {
            $joinDeclaration->isRoot = false;
        }

        // Check for ad-hoc Join conditions
        if ($adhocConditions) {
            $this->match(TokenType::T_WITH);

            $join->conditionalExpression = $this->ConditionalExpression();
        }

        return $join;
    }

    /**
     * RangeVariableDeclaration ::= AbstractSchemaName ["AS"] AliasIdentificationVariable
     *
     * @return AST\RangeVariableDeclaration
     *
     * @throws QueryException
     */
    public function RangeVariableDeclaration()
    {
        if ($this->lexer->isNextToken(TokenType::T_OPEN_PARENTHESIS) && $this->lexer->glimpse()->type === TokenType::T_SELECT) {
            $this->semanticalError('Subquery is not supported here', $this->lexer->token);
        }

        $abstractSchemaName = $this->AbstractSchemaName();

        $this->validateAbstractSchemaName($abstractSchemaName);

        if ($this->lexer->isNextToken(TokenType::T_AS)) {
            $this->match(TokenType::T_AS);
        }

        assert($this->lexer->lookahead !== null);
        $token                       = $this->lexer->lookahead;
        $aliasIdentificationVariable = $this->AliasIdentificationVariable();
        $classMetadata               = $this->em->getClassMetadata($abstractSchemaName);

        // Building queryComponent
        $queryComponent = [
            'metadata'     => $classMetadata,
            'parent'       => null,
            'relation'     => null,
            'map'          => null,
            'nestingLevel' => $this->nestingLevel,
            'token'        => $token,
        ];

        $this->queryComponents[$aliasIdentificationVariable] = $queryComponent;

        return new AST\RangeVariableDeclaration($abstractSchemaName, $aliasIdentificationVariable);
    }

    /**
     * JoinAssociationDeclaration ::= JoinAssociationPathExpression ["AS"] AliasIdentificationVariable [IndexBy]
     *
     * @return AST\JoinAssociationDeclaration
     */
    public function JoinAssociationDeclaration()
    {
        $joinAssociationPathExpression = $this->JoinAssociationPathExpression();

        if ($this->lexer->isNextToken(TokenType::T_AS)) {
            $this->match(TokenType::T_AS);
        }

        assert($this->lexer->lookahead !== null);

        $aliasIdentificationVariable = $this->AliasIdentificationVariable();
        $indexBy                     = $this->lexer->isNextToken(TokenType::T_INDEX) ? $this->IndexBy() : null;

        $identificationVariable = $joinAssociationPathExpression->identificationVariable;
        $field                  = $joinAssociationPathExpression->associationField;

        $class       = $this->getMetadataForDqlAlias($identificationVariable);
        $targetClass = $this->em->getClassMetadata($class->associationMappings[$field]['targetEntity']);

        // Building queryComponent
        $joinQueryComponent = [
            'metadata'     => $targetClass,
            'parent'       => $joinAssociationPathExpression->identificationVariable,
            'relation'     => $class->getAssociationMapping($field),
            'map'          => null,
            'nestingLevel' => $this->nestingLevel,
            'token'        => $this->lexer->lookahead,
        ];

        $this->queryComponents[$aliasIdentificationVariable] = $joinQueryComponent;

        return new AST\JoinAssociationDeclaration($joinAssociationPathExpression, $aliasIdentificationVariable, $indexBy);
    }

    /**
     * PartialObjectExpression ::= "PARTIAL" IdentificationVariable "." PartialFieldSet
     * PartialFieldSet ::= "{" SimpleStateField {"," SimpleStateField}* "}"
     *
     * @return AST\PartialObjectExpression
     */
    public function PartialObjectExpression()
    {
        $this->match(TokenType::T_PARTIAL);

        $partialFieldSet = [];

        $identificationVariable = $this->IdentificationVariable();

        $this->match(TokenType::T_DOT);
        $this->match(TokenType::T_OPEN_CURLY_BRACE);
        $this->match(TokenType::T_IDENTIFIER);

        assert($this->lexer->token !== null);
        $field = $this->lexer->token->value;

        // First field in partial expression might be embeddable property
        while ($this->lexer->isNextToken(TokenType::T_DOT)) {
            $this->match(TokenType::T_DOT);
            $this->match(TokenType::T_IDENTIFIER);
            $field .= '.' . $this->lexer->token->value;
        }

        $partialFieldSet[] = $field;

        while ($this->lexer->isNextToken(TokenType::T_COMMA)) {
            $this->match(TokenType::T_COMMA);
            $this->match(TokenType::T_IDENTIFIER);

            $field = $this->lexer->token->value;

            while ($this->lexer->isNextToken(TokenType::T_DOT)) {
                $this->match(TokenType::T_DOT);
                $this->match(TokenType::T_IDENTIFIER);
                $field .= '.' . $this->lexer->token->value;
            }

            $partialFieldSet[] = $field;
        }

        $this->match(TokenType::T_CLOSE_CURLY_BRACE);

        $partialObjectExpression = new AST\PartialObjectExpression($identificationVariable, $partialFieldSet);

        // Defer PartialObjectExpression validation
        $this->deferredPartialObjectExpressions[] = [
            'expression'   => $partialObjectExpression,
            'nestingLevel' => $this->nestingLevel,
            'token'        => $this->lexer->token,
        ];

        return $partialObjectExpression;
    }

    /**
     * NewObjectExpression ::= "NEW" AbstractSchemaName "(" NewObjectArg {"," NewObjectArg}* ")"
     *
     * @return AST\NewObjectExpression
     */
    public function NewObjectExpression()
    {
        $this->match(TokenType::T_NEW);

        $className = $this->AbstractSchemaName(); // note that this is not yet validated
        $token     = $this->lexer->token;

        $this->match(TokenType::T_OPEN_PARENTHESIS);

        $args[] = $this->NewObjectArg();

        while ($this->lexer->isNextToken(TokenType::T_COMMA)) {
            $this->match(TokenType::T_COMMA);

            $args[] = $this->NewObjectArg();
        }

        $this->match(TokenType::T_CLOSE_PARENTHESIS);

        $expression = new AST\NewObjectExpression($className, $args);

        // Defer NewObjectExpression validation
        $this->deferredNewObjectExpressions[] = [
            'token'        => $token,
            'expression'   => $expression,
            'nestingLevel' => $this->nestingLevel,
        ];

        return $expression;
    }

    /**
     * NewObjectArg ::= ScalarExpression | "(" Subselect ")"
     *
     * @return mixed
     */
    public function NewObjectArg()
    {
        assert($this->lexer->lookahead !== null);
        $token = $this->lexer->lookahead;
        $peek  = $this->lexer->glimpse();

        assert($peek !== null);
        if ($token->type === TokenType::T_OPEN_PARENTHESIS && $peek->type === TokenType::T_SELECT) {
            $this->match(TokenType::T_OPEN_PARENTHESIS);
            $expression = $this->Subselect();
            $this->match(TokenType::T_CLOSE_PARENTHESIS);

            return $expression;
        }

        return $this->ScalarExpression();
    }

    /**
     * IndexBy ::= "INDEX" "BY" SingleValuedPathExpression
     *
     * @return AST\IndexBy
     */
    public function IndexBy()
    {
        $this->match(TokenType::T_INDEX);
        $this->match(TokenType::T_BY);
        $pathExpr = $this->SingleValuedPathExpression();

        // Add the INDEX BY info to the query component
        $this->queryComponents[$pathExpr->identificationVariable]['map'] = $pathExpr->field;

        return new AST\IndexBy($pathExpr);
    }

    /**
     * ScalarExpression ::= SimpleArithmeticExpression | StringPrimary | DateTimePrimary |
     *                      StateFieldPathExpression | BooleanPrimary | CaseExpression |
     *                      InstanceOfExpression
     *
     * @return mixed One of the possible expressions or subexpressions.
     */
    public function ScalarExpression()
    {
        assert($this->lexer->token !== null);
        assert($this->lexer->lookahead !== null);
        $lookahead = $this->lexer->lookahead->type;
        $peek      = $this->lexer->glimpse();

        switch (true) {
            case $lookahead === TokenType::T_INTEGER:
            case $lookahead === TokenType::T_FLOAT:
            // SimpleArithmeticExpression : (- u.value ) or ( + u.value )  or ( - 1 ) or ( + 1 )
            case $lookahead === TokenType::T_MINUS:
            case $lookahead === TokenType::T_PLUS:
                return $this->SimpleArithmeticExpression();

            case $lookahead === TokenType::T_STRING:
                return $this->StringPrimary();

            case $lookahead === TokenType::T_TRUE:
            case $lookahead === TokenType::T_FALSE:
                $this->match($lookahead);

                return new AST\Literal(AST\Literal::BOOLEAN, $this->lexer->token->value);

            case $lookahead === TokenType::T_INPUT_PARAMETER:
                switch (true) {
                    case $this->isMathOperator($peek):
                        // :param + u.value
                        return $this->SimpleArithmeticExpression();

                    default:
                        return $this->InputParameter();
                }
            case $lookahead === TokenType::T_CASE:
            case $lookahead === TokenType::T_COALESCE:
            case $lookahead === TokenType::T_NULLIF:
                // Since NULLIF and COALESCE can be identified as a function,
                // we need to check these before checking for FunctionDeclaration
                return $this->CaseExpression();

            case $lookahead === TokenType::T_OPEN_PARENTHESIS:
                return $this->SimpleArithmeticExpression();

            // this check must be done before checking for a filed path expression
            case $this->isFunction():
                $this->lexer->peek(); // "("

                switch (true) {
                    case $this->isMathOperator($this->peekBeyondClosingParenthesis()):
                        // SUM(u.id) + COUNT(u.id)
                        return $this->SimpleArithmeticExpression();

                    default:
                        // IDENTITY(u)
                        return $this->FunctionDeclaration();
                }

                break;
            // it is no function, so it must be a field path
            case $lookahead === TokenType::T_IDENTIFIER:
                $this->lexer->peek(); // lookahead => '.'
                $this->lexer->peek(); // lookahead => token after '.'
                $peek = $this->lexer->peek(); // lookahead => token after the token after the '.'
                $this->lexer->resetPeek();

                if ($this->isMathOperator($peek)) {
                    return $this->SimpleArithmeticExpression();
                }

                return $this->StateFieldPathExpression();

            default:
                $this->syntaxError();
        }
    }

    /**
     * CaseExpression ::= GeneralCaseExpression | SimpleCaseExpression | CoalesceExpression | NullifExpression
     * GeneralCaseExpression ::= "CASE" WhenClause {WhenClause}* "ELSE" ScalarExpression "END"
     * WhenClause ::= "WHEN" ConditionalExpression "THEN" ScalarExpression
     * SimpleCaseExpression ::= "CASE" CaseOperand SimpleWhenClause {SimpleWhenClause}* "ELSE" ScalarExpression "END"
     * CaseOperand ::= StateFieldPathExpression | TypeDiscriminator
     * SimpleWhenClause ::= "WHEN" ScalarExpression "THEN" ScalarExpression
     * CoalesceExpression ::= "COALESCE" "(" ScalarExpression {"," ScalarExpression}* ")"
     * NullifExpression ::= "NULLIF" "(" ScalarExpression "," ScalarExpression ")"
     *
     * @return mixed One of the possible expressions or subexpressions.
     */
    public function CaseExpression()
    {
        assert($this->lexer->lookahead !== null);
        $lookahead = $this->lexer->lookahead->type;

        switch ($lookahead) {
            case TokenType::T_NULLIF:
                return $this->NullIfExpression();

            case TokenType::T_COALESCE:
                return $this->CoalesceExpression();

            case TokenType::T_CASE:
                $this->lexer->resetPeek();
                $peek = $this->lexer->peek();

                assert($peek !== null);
                if ($peek->type === TokenType::T_WHEN) {
                    return $this->GeneralCaseExpression();
                }

                return $this->SimpleCaseExpression();

            default:
                // Do nothing
                break;
        }

        $this->syntaxError();
    }

    /**
     * CoalesceExpression ::= "COALESCE" "(" ScalarExpression {"," ScalarExpression}* ")"
     *
     * @return AST\CoalesceExpression
     */
    public function CoalesceExpression()
    {
        $this->match(TokenType::T_COALESCE);
        $this->match(TokenType::T_OPEN_PARENTHESIS);

        // Process ScalarExpressions (1..N)
        $scalarExpressions   = [];
        $scalarExpressions[] = $this->ScalarExpression();

        while ($this->lexer->isNextToken(TokenType::T_COMMA)) {
            $this->match(TokenType::T_COMMA);

            $scalarExpressions[] = $this->ScalarExpression();
        }

        $this->match(TokenType::T_CLOSE_PARENTHESIS);

        return new AST\CoalesceExpression($scalarExpressions);
    }

    /**
     * NullIfExpression ::= "NULLIF" "(" ScalarExpression "," ScalarExpression ")"
     *
     * @return AST\NullIfExpression
     */
    public function NullIfExpression()
    {
        $this->match(TokenType::T_NULLIF);
        $this->match(TokenType::T_OPEN_PARENTHESIS);

        $firstExpression = $this->ScalarExpression();
        $this->match(TokenType::T_COMMA);
        $secondExpression = $this->ScalarExpression();

        $this->match(TokenType::T_CLOSE_PARENTHESIS);

        return new AST\NullIfExpression($firstExpression, $secondExpression);
    }

    /**
     * GeneralCaseExpression ::= "CASE" WhenClause {WhenClause}* "ELSE" ScalarExpression "END"
     *
     * @return AST\GeneralCaseExpression
     */
    public function GeneralCaseExpression()
    {
        $this->match(TokenType::T_CASE);

        // Process WhenClause (1..N)
        $whenClauses = [];

        do {
            $whenClauses[] = $this->WhenClause();
        } while ($this->lexer->isNextToken(TokenType::T_WHEN));

        $this->match(TokenType::T_ELSE);
        $scalarExpression = $this->ScalarExpression();
        $this->match(TokenType::T_END);

        return new AST\GeneralCaseExpression($whenClauses, $scalarExpression);
    }

    /**
     * SimpleCaseExpression ::= "CASE" CaseOperand SimpleWhenClause {SimpleWhenClause}* "ELSE" ScalarExpression "END"
     * CaseOperand ::= StateFieldPathExpression | TypeDiscriminator
     *
     * @return AST\SimpleCaseExpression
     */
    public function SimpleCaseExpression()
    {
        $this->match(TokenType::T_CASE);
        $caseOperand = $this->StateFieldPathExpression();

        // Process SimpleWhenClause (1..N)
        $simpleWhenClauses = [];

        do {
            $simpleWhenClauses[] = $this->SimpleWhenClause();
        } while ($this->lexer->isNextToken(TokenType::T_WHEN));

        $this->match(TokenType::T_ELSE);
        $scalarExpression = $this->ScalarExpression();
        $this->match(TokenType::T_END);

        return new AST\SimpleCaseExpression($caseOperand, $simpleWhenClauses, $scalarExpression);
    }

    /**
     * WhenClause ::= "WHEN" ConditionalExpression "THEN" ScalarExpression
     *
     * @return AST\WhenClause
     */
    public function WhenClause()
    {
        $this->match(TokenType::T_WHEN);
        $conditionalExpression = $this->ConditionalExpression();
        $this->match(TokenType::T_THEN);

        return new AST\WhenClause($conditionalExpression, $this->ScalarExpression());
    }

    /**
     * SimpleWhenClause ::= "WHEN" ScalarExpression "THEN" ScalarExpression
     *
     * @return AST\SimpleWhenClause
     */
    public function SimpleWhenClause()
    {
        $this->match(TokenType::T_WHEN);
        $conditionalExpression = $this->ScalarExpression();
        $this->match(TokenType::T_THEN);

        return new AST\SimpleWhenClause($conditionalExpression, $this->ScalarExpression());
    }

    /**
     * SelectExpression ::= (
     *     IdentificationVariable | ScalarExpression | AggregateExpression | FunctionDeclaration |
     *     PartialObjectExpression | "(" Subselect ")" | CaseExpression | NewObjectExpression
     * ) [["AS"] ["HIDDEN"] AliasResultVariable]
     *
     * @return AST\SelectExpression
     */
    public function SelectExpression()
    {
        assert($this->lexer->lookahead !== null);
        $expression    = null;
        $identVariable = null;
        $peek          = $this->lexer->glimpse();
        $lookaheadType = $this->lexer->lookahead->type;
        assert($peek !== null);

        switch (true) {
            // ScalarExpression (u.name)
            case $lookaheadType === TokenType::T_IDENTIFIER && $peek->type === TokenType::T_DOT:
                $expression = $this->ScalarExpression();
                break;

            // IdentificationVariable (u)
            case $lookaheadType === TokenType::T_IDENTIFIER && $peek->type !== TokenType::T_OPEN_PARENTHESIS:
                $expression = $identVariable = $this->IdentificationVariable();
                break;

            // CaseExpression (CASE ... or NULLIF(...) or COALESCE(...))
            case $lookaheadType === TokenType::T_CASE:
            case $lookaheadType === TokenType::T_COALESCE:
            case $lookaheadType === TokenType::T_NULLIF:
                $expression = $this->CaseExpression();
                break;

            // DQL Function (SUM(u.value) or SUM(u.value) + 1)
            case $this->isFunction():
                $this->lexer->peek(); // "("

                switch (true) {
                    case $this->isMathOperator($this->peekBeyondClosingParenthesis()):
                        // SUM(u.id) + COUNT(u.id)
                        $expression = $this->ScalarExpression();
                        break;

                    default:
                        // IDENTITY(u)
                        $expression = $this->FunctionDeclaration();
                        break;
                }

                break;

            // PartialObjectExpression (PARTIAL u.{id, name})
            case $lookaheadType === TokenType::T_PARTIAL:
                $expression    = $this->PartialObjectExpression();
                $identVariable = $expression->identificationVariable;
                break;

            // Subselect
            case $lookaheadType === TokenType::T_OPEN_PARENTHESIS && $peek->type === TokenType::T_SELECT:
                $this->match(TokenType::T_OPEN_PARENTHESIS);
                $expression = $this->Subselect();
                $this->match(TokenType::T_CLOSE_PARENTHESIS);
                break;

            // Shortcut: ScalarExpression => SimpleArithmeticExpression
            case $lookaheadType === TokenType::T_OPEN_PARENTHESIS:
            case $lookaheadType === TokenType::T_INTEGER:
            case $lookaheadType === TokenType::T_STRING:
            case $lookaheadType === TokenType::T_FLOAT:
            // SimpleArithmeticExpression : (- u.value ) or ( + u.value )
            case $lookaheadType === TokenType::T_MINUS:
            case $lookaheadType === TokenType::T_PLUS:
                $expression = $this->SimpleArithmeticExpression();
                break;

            // NewObjectExpression (New ClassName(id, name))
            case $lookaheadType === TokenType::T_NEW:
                $expression = $this->NewObjectExpression();
                break;

            default:
                $this->syntaxError(
                    'IdentificationVariable | ScalarExpression | AggregateExpression | FunctionDeclaration | PartialObjectExpression | "(" Subselect ")" | CaseExpression',
                    $this->lexer->lookahead
                );
        }

        // [["AS"] ["HIDDEN"] AliasResultVariable]
        $mustHaveAliasResultVariable = false;

        if ($this->lexer->isNextToken(TokenType::T_AS)) {
            $this->match(TokenType::T_AS);

            $mustHaveAliasResultVariable = true;
        }

        $hiddenAliasResultVariable = false;

        if ($this->lexer->isNextToken(TokenType::T_HIDDEN)) {
            $this->match(TokenType::T_HIDDEN);

            $hiddenAliasResultVariable = true;
        }

        $aliasResultVariable = null;

        if ($mustHaveAliasResultVariable || $this->lexer->isNextToken(TokenType::T_IDENTIFIER)) {
            assert($expression instanceof AST\Node || is_string($expression));
            $token               = $this->lexer->lookahead;
            $aliasResultVariable = $this->AliasResultVariable();

            // Include AliasResultVariable in query components.
            $this->queryComponents[$aliasResultVariable] = [
                'resultVariable' => $expression,
                'nestingLevel'   => $this->nestingLevel,
                'token'          => $token,
            ];
        }

        // AST

        $expr = new AST\SelectExpression($expression, $aliasResultVariable, $hiddenAliasResultVariable);

        if ($identVariable) {
            $this->identVariableExpressions[$identVariable] = $expr;
        }

        return $expr;
    }

    /**
     * SimpleSelectExpression ::= (
     *      StateFieldPathExpression | IdentificationVariable | FunctionDeclaration |
     *      AggregateExpression | "(" Subselect ")" | ScalarExpression
     * ) [["AS"] AliasResultVariable]
     *
     * @return AST\SimpleSelectExpression
     */
    public function SimpleSelectExpression()
    {
        assert($this->lexer->lookahead !== null);
        $peek = $this->lexer->glimpse();
        assert($peek !== null);

        switch ($this->lexer->lookahead->type) {
            case TokenType::T_IDENTIFIER:
                switch (true) {
                    case $peek->type === TokenType::T_DOT:
                        $expression = $this->StateFieldPathExpression();

                        return new AST\SimpleSelectExpression($expression);

                    case $peek->type !== TokenType::T_OPEN_PARENTHESIS:
                        $expression = $this->IdentificationVariable();

                        return new AST\SimpleSelectExpression($expression);

                    case $this->isFunction():
                        // SUM(u.id) + COUNT(u.id)
                        if ($this->isMathOperator($this->peekBeyondClosingParenthesis())) {
                            return new AST\SimpleSelectExpression($this->ScalarExpression());
                        }

                        // COUNT(u.id)
                        if ($this->isAggregateFunction($this->lexer->lookahead->type)) {
                            return new AST\SimpleSelectExpression($this->AggregateExpression());
                        }

                        // IDENTITY(u)
                        return new AST\SimpleSelectExpression($this->FunctionDeclaration());

                    default:
                        // Do nothing
                }

                break;

            case TokenType::T_OPEN_PARENTHESIS:
                if ($peek->type !== TokenType::T_SELECT) {
                    // Shortcut: ScalarExpression => SimpleArithmeticExpression
                    $expression = $this->SimpleArithmeticExpression();

                    return new AST\SimpleSelectExpression($expression);
                }

                // Subselect
                $this->match(TokenType::T_OPEN_PARENTHESIS);
                $expression = $this->Subselect();
                $this->match(TokenType::T_CLOSE_PARENTHESIS);

                return new AST\SimpleSelectExpression($expression);

            default:
                // Do nothing
        }

        $this->lexer->peek();

        $expression = $this->ScalarExpression();
        $expr       = new AST\SimpleSelectExpression($expression);

        if ($this->lexer->isNextToken(TokenType::T_AS)) {
            $this->match(TokenType::T_AS);
        }

        if ($this->lexer->isNextToken(TokenType::T_IDENTIFIER)) {
            $token                             = $this->lexer->lookahead;
            $resultVariable                    = $this->AliasResultVariable();
            $expr->fieldIdentificationVariable = $resultVariable;

            // Include AliasResultVariable in query components.
            $this->queryComponents[$resultVariable] = [
                'resultvariable' => $expr,
                'nestingLevel'   => $this->nestingLevel,
                'token'          => $token,
            ];
        }

        return $expr;
    }

    /**
     * ConditionalExpression ::= ConditionalTerm {"OR" ConditionalTerm}*
     *
     * @return AST\ConditionalExpression|AST\ConditionalFactor|AST\ConditionalPrimary|AST\ConditionalTerm
     */
    public function ConditionalExpression()
    {
        $conditionalTerms   = [];
        $conditionalTerms[] = $this->ConditionalTerm();

        while ($this->lexer->isNextToken(TokenType::T_OR)) {
            $this->match(TokenType::T_OR);

            $conditionalTerms[] = $this->ConditionalTerm();
        }

        // Phase 1 AST optimization: Prevent AST\ConditionalExpression
        // if only one AST\ConditionalTerm is defined
        if (count($conditionalTerms) === 1) {
            return $conditionalTerms[0];
        }

        return new AST\ConditionalExpression($conditionalTerms);
    }

    /**
     * ConditionalTerm ::= ConditionalFactor {"AND" ConditionalFactor}*
     *
     * @return AST\ConditionalFactor|AST\ConditionalPrimary|AST\ConditionalTerm
     */
    public function ConditionalTerm()
    {
        $conditionalFactors   = [];
        $conditionalFactors[] = $this->ConditionalFactor();

        while ($this->lexer->isNextToken(TokenType::T_AND)) {
            $this->match(TokenType::T_AND);

            $conditionalFactors[] = $this->ConditionalFactor();
        }

        // Phase 1 AST optimization: Prevent AST\ConditionalTerm
        // if only one AST\ConditionalFactor is defined
        if (count($conditionalFactors) === 1) {
            return $conditionalFactors[0];
        }

        return new AST\ConditionalTerm($conditionalFactors);
    }

    /**
     * ConditionalFactor ::= ["NOT"] ConditionalPrimary
     *
     * @return AST\ConditionalFactor|AST\ConditionalPrimary
     */
    public function ConditionalFactor()
    {
        $not = false;

        if ($this->lexer->isNextToken(TokenType::T_NOT)) {
            $this->match(TokenType::T_NOT);

            $not = true;
        }

        $conditionalPrimary = $this->ConditionalPrimary();

        // Phase 1 AST optimization: Prevent AST\ConditionalFactor
        // if only one AST\ConditionalPrimary is defined
        if (! $not) {
            return $conditionalPrimary;
        }

        return new AST\ConditionalFactor($conditionalPrimary, $not);
    }

    /**
     * ConditionalPrimary ::= SimpleConditionalExpression | "(" ConditionalExpression ")"
     *
     * @return AST\ConditionalPrimary
     */
    public function ConditionalPrimary()
    {
        $condPrimary = new AST\ConditionalPrimary();

        if (! $this->lexer->isNextToken(TokenType::T_OPEN_PARENTHESIS)) {
            $condPrimary->simpleConditionalExpression = $this->SimpleConditionalExpression();

            return $condPrimary;
        }

        // Peek beyond the matching closing parenthesis ')'
        $peek = $this->peekBeyondClosingParenthesis();

        if (
            $peek !== null && (
            in_array($peek->value, ['=', '<', '<=', '<>', '>', '>=', '!='], true) ||
            in_array($peek->type, [TokenType::T_NOT, TokenType::T_BETWEEN, TokenType::T_LIKE, TokenType::T_IN, TokenType::T_IS, TokenType::T_EXISTS], true) ||
            $this->isMathOperator($peek)
            )
        ) {
            $condPrimary->simpleConditionalExpression = $this->SimpleConditionalExpression();

            return $condPrimary;
        }

        $this->match(TokenType::T_OPEN_PARENTHESIS);
        $condPrimary->conditionalExpression = $this->ConditionalExpression();
        $this->match(TokenType::T_CLOSE_PARENTHESIS);

        return $condPrimary;
    }

    /**
     * SimpleConditionalExpression ::=
     *      ComparisonExpression | BetweenExpression | LikeExpression |
     *      InExpression | NullComparisonExpression | ExistsExpression |
     *      EmptyCollectionComparisonExpression | CollectionMemberExpression |
     *      InstanceOfExpression
     *
     * @return (AST\BetweenExpression|
     *         AST\CollectionMemberExpression|
     *         AST\ComparisonExpression|
     *         AST\EmptyCollectionComparisonExpression|
     *         AST\ExistsExpression|
     *         AST\InExpression|
     *         AST\InstanceOfExpression|
     *         AST\LikeExpression|
     *         AST\NullComparisonExpression)
     *
     * @phpstan-ignore return.deprecatedClass
     */
    public function SimpleConditionalExpression()
    {
        assert($this->lexer->lookahead !== null);
        if ($this->lexer->isNextToken(TokenType::T_EXISTS)) {
            return $this->ExistsExpression();
        }

        $token     = $this->lexer->lookahead;
        $peek      = $this->lexer->glimpse();
        $lookahead = $token;

        if ($this->lexer->isNextToken(TokenType::T_NOT)) {
            $token = $this->lexer->glimpse();
        }

        assert($token !== null);
        assert($peek !== null);
        if ($token->type === TokenType::T_IDENTIFIER || $token->type === TokenType::T_INPUT_PARAMETER || $this->isFunction()) {
            // Peek beyond the matching closing parenthesis.
            $beyond = $this->lexer->peek();

            switch ($peek->value) {
                case '(':
                    // Peeks beyond the matched closing parenthesis.
                    $token = $this->peekBeyondClosingParenthesis(false);
                    assert($token !== null);

                    if ($token->type === TokenType::T_NOT) {
                        $token = $this->lexer->peek();
                        assert($token !== null);
                    }

                    if ($token->type === TokenType::T_IS) {
                        $lookahead = $this->lexer->peek();
                    }

                    break;

                default:
                    // Peek beyond the PathExpression or InputParameter.
                    $token = $beyond;

                    while ($token->value === '.') {
                        $this->lexer->peek();

                        $token = $this->lexer->peek();
                        assert($token !== null);
                    }

                    // Also peek beyond a NOT if there is one.
                    assert($token !== null);
                    if ($token->type === TokenType::T_NOT) {
                        $token = $this->lexer->peek();
                        assert($token !== null);
                    }

                    // We need to go even further in case of IS (differentiate between NULL and EMPTY)
                    $lookahead = $this->lexer->peek();
            }

            assert($lookahead !== null);
            // Also peek beyond a NOT if there is one.
            if ($lookahead->type === TokenType::T_NOT) {
                $lookahead = $this->lexer->peek();
            }

            $this->lexer->resetPeek();
        }

        if ($token->type === TokenType::T_BETWEEN) {
            return $this->BetweenExpression();
        }

        if ($token->type === TokenType::T_LIKE) {
            return $this->LikeExpression();
        }

        if ($token->type === TokenType::T_IN) {
            return $this->InExpression();
        }

        if ($token->type === TokenType::T_INSTANCE) {
            return $this->InstanceOfExpression();
        }

        if ($token->type === TokenType::T_MEMBER) {
            return $this->CollectionMemberExpression();
        }

        assert($lookahead !== null);
        if ($token->type === TokenType::T_IS && $lookahead->type === TokenType::T_NULL) {
            return $this->NullComparisonExpression();
        }

        if ($token->type === TokenType::T_IS && $lookahead->type === TokenType::T_EMPTY) {
            return $this->EmptyCollectionComparisonExpression();
        }

        return $this->ComparisonExpression();
    }

    /**
     * EmptyCollectionComparisonExpression ::= CollectionValuedPathExpression "IS" ["NOT"] "EMPTY"
     *
     * @return AST\EmptyCollectionComparisonExpression
     */
    public function EmptyCollectionComparisonExpression()
    {
        $pathExpression = $this->CollectionValuedPathExpression();
        $this->match(TokenType::T_IS);

        $not = false;
        if ($this->lexer->isNextToken(TokenType::T_NOT)) {
            $this->match(TokenType::T_NOT);
            $not = true;
        }

        $this->match(TokenType::T_EMPTY);

        return new AST\EmptyCollectionComparisonExpression(
            $pathExpression,
            $not
        );
    }

    /**
     * CollectionMemberExpression ::= EntityExpression ["NOT"] "MEMBER" ["OF"] CollectionValuedPathExpression
     *
     * EntityExpression ::= SingleValuedAssociationPathExpression | SimpleEntityExpression
     * SimpleEntityExpression ::= IdentificationVariable | InputParameter
     *
     * @return AST\CollectionMemberExpression
     */
    public function CollectionMemberExpression()
    {
        $not        = false;
        $entityExpr = $this->EntityExpression();

        if ($this->lexer->isNextToken(TokenType::T_NOT)) {
            $this->match(TokenType::T_NOT);

            $not = true;
        }

        $this->match(TokenType::T_MEMBER);

        if ($this->lexer->isNextToken(TokenType::T_OF)) {
            $this->match(TokenType::T_OF);
        }

        return new AST\CollectionMemberExpression(
            $entityExpr,
            $this->CollectionValuedPathExpression(),
            $not
        );
    }

    /**
     * Literal ::= string | char | integer | float | boolean
     *
     * @return AST\Literal
     */
    public function Literal()
    {
        assert($this->lexer->lookahead !== null);
        assert($this->lexer->token !== null);
        switch ($this->lexer->lookahead->type) {
            case TokenType::T_STRING:
                $this->match(TokenType::T_STRING);

                return new AST\Literal(AST\Literal::STRING, $this->lexer->token->value);

            case TokenType::T_INTEGER:
            case TokenType::T_FLOAT:
                $this->match(
                    $this->lexer->isNextToken(TokenType::T_INTEGER) ? TokenType::T_INTEGER : TokenType::T_FLOAT
                );

                return new AST\Literal(AST\Literal::NUMERIC, $this->lexer->token->value);

            case TokenType::T_TRUE:
            case TokenType::T_FALSE:
                $this->match(
                    $this->lexer->isNextToken(TokenType::T_TRUE) ? TokenType::T_TRUE : TokenType::T_FALSE
                );

                return new AST\Literal(AST\Literal::BOOLEAN, $this->lexer->token->value);

            default:
                $this->syntaxError('Literal');
        }
    }

    /**
     * InParameter ::= ArithmeticExpression | InputParameter
     *
     * @return AST\InputParameter|AST\ArithmeticExpression
     */
    public function InParameter()
    {
        assert($this->lexer->lookahead !== null);
        if ($this->lexer->lookahead->type === TokenType::T_INPUT_PARAMETER) {
            return $this->InputParameter();
        }

        return $this->ArithmeticExpression();
    }

    /**
     * InputParameter ::= PositionalParameter | NamedParameter
     *
     * @return AST\InputParameter
     */
    public function InputParameter()
    {
        $this->match(TokenType::T_INPUT_PARAMETER);
        assert($this->lexer->token !== null);

        return new AST\InputParameter($this->lexer->token->value);
    }

    /**
     * ArithmeticExpression ::= SimpleArithmeticExpression | "(" Subselect ")"
     *
     * @return AST\ArithmeticExpression
     */
    public function ArithmeticExpression()
    {
        $expr = new AST\ArithmeticExpression();

        if ($this->lexer->isNextToken(TokenType::T_OPEN_PARENTHESIS)) {
            $peek = $this->lexer->glimpse();
            assert($peek !== null);

            if ($peek->type === TokenType::T_SELECT) {
                $this->match(TokenType::T_OPEN_PARENTHESIS);
                $expr->subselect = $this->Subselect();
                $this->match(TokenType::T_CLOSE_PARENTHESIS);

                return $expr;
            }
        }

        $expr->simpleArithmeticExpression = $this->SimpleArithmeticExpression();

        return $expr;
    }

    /**
     * SimpleArithmeticExpression ::= ArithmeticTerm {("+" | "-") ArithmeticTerm}*
     *
     * @return AST\SimpleArithmeticExpression|AST\ArithmeticTerm
     */
    public function SimpleArithmeticExpression()
    {
        $terms   = [];
        $terms[] = $this->ArithmeticTerm();

        while (($isPlus = $this->lexer->isNextToken(TokenType::T_PLUS)) || $this->lexer->isNextToken(TokenType::T_MINUS)) {
            $this->match($isPlus ? TokenType::T_PLUS : TokenType::T_MINUS);

            assert($this->lexer->token !== null);
            $terms[] = $this->lexer->token->value;
            $terms[] = $this->ArithmeticTerm();
        }

        // Phase 1 AST optimization: Prevent AST\SimpleArithmeticExpression
        // if only one AST\ArithmeticTerm is defined
        if (count($terms) === 1) {
            return $terms[0];
        }

        return new AST\SimpleArithmeticExpression($terms);
    }

    /**
     * ArithmeticTerm ::= ArithmeticFactor {("*" | "/") ArithmeticFactor}*
     *
     * @return AST\ArithmeticTerm
     */
    public function ArithmeticTerm()
    {
        $factors   = [];
        $factors[] = $this->ArithmeticFactor();

        while (($isMult = $this->lexer->isNextToken(TokenType::T_MULTIPLY)) || $this->lexer->isNextToken(TokenType::T_DIVIDE)) {
            $this->match($isMult ? TokenType::T_MULTIPLY : TokenType::T_DIVIDE);

            assert($this->lexer->token !== null);
            $factors[] = $this->lexer->token->value;
            $factors[] = $this->ArithmeticFactor();
        }

        // Phase 1 AST optimization: Prevent AST\ArithmeticTerm
        // if only one AST\ArithmeticFactor is defined
        if (count($factors) === 1) {
            return $factors[0];
        }

        return new AST\ArithmeticTerm($factors);
    }

    /**
     * ArithmeticFactor ::= [("+" | "-")] ArithmeticPrimary
     *
     * @return AST\ArithmeticFactor
     */
    public function ArithmeticFactor()
    {
        $sign = null;

        $isPlus = $this->lexer->isNextToken(TokenType::T_PLUS);
        if ($isPlus || $this->lexer->isNextToken(TokenType::T_MINUS)) {
            $this->match($isPlus ? TokenType::T_PLUS : TokenType::T_MINUS);
            $sign = $isPlus;
        }

        $primary = $this->ArithmeticPrimary();

        // Phase 1 AST optimization: Prevent AST\ArithmeticFactor
        // if only one AST\ArithmeticPrimary is defined
        if ($sign === null) {
            return $primary;
        }

        return new AST\ArithmeticFactor($primary, $sign);
    }

    /**
     * ArithmeticPrimary ::= SingleValuedPathExpression | Literal | ParenthesisExpression
     *          | FunctionsReturningNumerics | AggregateExpression | FunctionsReturningStrings
     *          | FunctionsReturningDatetime | IdentificationVariable | ResultVariable
     *          | InputParameter | CaseExpression
     *
     * @return AST\Node|string
     */
    public function ArithmeticPrimary()
    {
        if ($this->lexer->isNextToken(TokenType::T_OPEN_PARENTHESIS)) {
            $this->match(TokenType::T_OPEN_PARENTHESIS);

            $expr = $this->SimpleArithmeticExpression();

            $this->match(TokenType::T_CLOSE_PARENTHESIS);

            return new AST\ParenthesisExpression($expr);
        }

        if ($this->lexer->lookahead === null) {
            $this->syntaxError('ArithmeticPrimary');
        }

        switch ($this->lexer->lookahead->type) {
            case TokenType::T_COALESCE:
            case TokenType::T_NULLIF:
            case TokenType::T_CASE:
                return $this->CaseExpression();

            case TokenType::T_IDENTIFIER:
                $peek = $this->lexer->glimpse();

                if ($peek !== null && $peek->value === '(') {
                    return $this->FunctionDeclaration();
                }

                if ($peek !== null && $peek->value === '.') {
                    return $this->SingleValuedPathExpression();
                }

                if (isset($this->queryComponents[$this->lexer->lookahead->value]['resultVariable'])) {
                    return $this->ResultVariable();
                }

                return $this->StateFieldPathExpression();

            case TokenType::T_INPUT_PARAMETER:
                return $this->InputParameter();

            default:
                $peek = $this->lexer->glimpse();

                if ($peek !== null && $peek->value === '(') {
                    return $this->FunctionDeclaration();
                }

                return $this->Literal();
        }
    }

    /**
     * StringExpression ::= StringPrimary | ResultVariable | "(" Subselect ")"
     *
     * @return AST\Subselect|AST\Node|string
     */
    public function StringExpression()
    {
        $peek = $this->lexer->glimpse();
        assert($peek !== null);

        // Subselect
        if ($this->lexer->isNextToken(TokenType::T_OPEN_PARENTHESIS) && $peek->type === TokenType::T_SELECT) {
            $this->match(TokenType::T_OPEN_PARENTHESIS);
            $expr = $this->Subselect();
            $this->match(TokenType::T_CLOSE_PARENTHESIS);

            return $expr;
        }

        assert($this->lexer->lookahead !== null);
        // ResultVariable (string)
        if (
            $this->lexer->isNextToken(TokenType::T_IDENTIFIER) &&
            isset($this->queryComponents[$this->lexer->lookahead->value]['resultVariable'])
        ) {
            return $this->ResultVariable();
        }

        return $this->StringPrimary();
    }

    /**
     * StringPrimary ::= StateFieldPathExpression | string | InputParameter | FunctionsReturningStrings | AggregateExpression | CaseExpression
     *
     * @return AST\Node
     */
    public function StringPrimary()
    {
        assert($this->lexer->lookahead !== null);
        $lookaheadType = $this->lexer->lookahead->type;

        switch ($lookaheadType) {
            case TokenType::T_IDENTIFIER:
                $peek = $this->lexer->glimpse();
                assert($peek !== null);

                if ($peek->value === '.') {
                    return $this->StateFieldPathExpression();
                }

                if ($peek->value === '(') {
                    // do NOT directly go to FunctionsReturningString() because it doesn't check for custom functions.
                    return $this->FunctionDeclaration();
                }

                $this->syntaxError("'.' or '('");
                break;

            case TokenType::T_STRING:
                $this->match(TokenType::T_STRING);
                assert($this->lexer->token !== null);

                return new AST\Literal(AST\Literal::STRING, $this->lexer->token->value);

            case TokenType::T_INPUT_PARAMETER:
                return $this->InputParameter();

            case TokenType::T_CASE:
            case TokenType::T_COALESCE:
            case TokenType::T_NULLIF:
                return $this->CaseExpression();

            default:
                assert($lookaheadType !== null);
                if ($this->isAggregateFunction($lookaheadType)) {
                    return $this->AggregateExpression();
                }
        }

        $this->syntaxError(
            'StateFieldPathExpression | string | InputParameter | FunctionsReturningStrings | AggregateExpression'
        );
    }

    /**
     * EntityExpression ::= SingleValuedAssociationPathExpression | SimpleEntityExpression
     *
     * @return AST\InputParameter|AST\PathExpression
     */
    public function EntityExpression()
    {
        $glimpse = $this->lexer->glimpse();
        assert($glimpse !== null);

        if ($this->lexer->isNextToken(TokenType::T_IDENTIFIER) && $glimpse->value === '.') {
            return $this->SingleValuedAssociationPathExpression();
        }

        return $this->SimpleEntityExpression();
    }

    /**
     * SimpleEntityExpression ::= IdentificationVariable | InputParameter
     *
     * @return AST\InputParameter|AST\PathExpression
     */
    public function SimpleEntityExpression()
    {
        if ($this->lexer->isNextToken(TokenType::T_INPUT_PARAMETER)) {
            return $this->InputParameter();
        }

        return $this->StateFieldPathExpression();
    }

    /**
     * AggregateExpression ::=
     *  ("AVG" | "MAX" | "MIN" | "SUM" | "COUNT") "(" ["DISTINCT"] SimpleArithmeticExpression ")"
     *
     * @return AST\AggregateExpression
     */
    public function AggregateExpression()
    {
        assert($this->lexer->lookahead !== null);
        $lookaheadType = $this->lexer->lookahead->type;
        $isDistinct    = false;

        if (! in_array($lookaheadType, [TokenType::T_COUNT, TokenType::T_AVG, TokenType::T_MAX, TokenType::T_MIN, TokenType::T_SUM], true)) {
            $this->syntaxError('One of: MAX, MIN, AVG, SUM, COUNT');
        }

        $this->match($lookaheadType);
        assert($this->lexer->token !== null);
        $functionName = $this->lexer->token->value;
        $this->match(TokenType::T_OPEN_PARENTHESIS);

        if ($this->lexer->isNextToken(TokenType::T_DISTINCT)) {
            $this->match(TokenType::T_DISTINCT);
            $isDistinct = true;
        }

        $pathExp = $this->SimpleArithmeticExpression();

        $this->match(TokenType::T_CLOSE_PARENTHESIS);

        return new AST\AggregateExpression($functionName, $pathExp, $isDistinct);
    }

    /**
     * QuantifiedExpression ::= ("ALL" | "ANY" | "SOME") "(" Subselect ")"
     *
     * @return AST\QuantifiedExpression
     */
    public function QuantifiedExpression()
    {
        assert($this->lexer->lookahead !== null);
        $lookaheadType = $this->lexer->lookahead->type;
        $value         = $this->lexer->lookahead->value;

        if (! in_array($lookaheadType, [TokenType::T_ALL, TokenType::T_ANY, TokenType::T_SOME], true)) {
            $this->syntaxError('ALL, ANY or SOME');
        }

        $this->match($lookaheadType);
        $this->match(TokenType::T_OPEN_PARENTHESIS);

        $qExpr       = new AST\QuantifiedExpression($this->Subselect());
        $qExpr->type = $value;

        $this->match(TokenType::T_CLOSE_PARENTHESIS);

        return $qExpr;
    }

    /**
     * BetweenExpression ::= ArithmeticExpression ["NOT"] "BETWEEN" ArithmeticExpression "AND" ArithmeticExpression
     *
     * @return AST\BetweenExpression
     */
    public function BetweenExpression()
    {
        $not        = false;
        $arithExpr1 = $this->ArithmeticExpression();

        if ($this->lexer->isNextToken(TokenType::T_NOT)) {
            $this->match(TokenType::T_NOT);
            $not = true;
        }

        $this->match(TokenType::T_BETWEEN);
        $arithExpr2 = $this->ArithmeticExpression();
        $this->match(TokenType::T_AND);
        $arithExpr3 = $this->ArithmeticExpression();

        return new AST\BetweenExpression($arithExpr1, $arithExpr2, $arithExpr3, $not);
    }

    /**
     * ComparisonExpression ::= ArithmeticExpression ComparisonOperator ( QuantifiedExpression | ArithmeticExpression )
     *
     * @return AST\ComparisonExpression
     */
    public function ComparisonExpression()
    {
        $this->lexer->glimpse();

        $leftExpr  = $this->ArithmeticExpression();
        $operator  = $this->ComparisonOperator();
        $rightExpr = $this->isNextAllAnySome()
            ? $this->QuantifiedExpression()
            : $this->ArithmeticExpression();

        return new AST\ComparisonExpression($leftExpr, $operator, $rightExpr);
    }

    /**
     * InExpression ::= SingleValuedPathExpression ["NOT"] "IN" "(" (InParameter {"," InParameter}* | Subselect) ")"
     *
     * @return AST\InListExpression|AST\InSubselectExpression
     */
    public function InExpression()
    {
        $expression = $this->ArithmeticExpression();

        $not = false;
        if ($this->lexer->isNextToken(TokenType::T_NOT)) {
            $this->match(TokenType::T_NOT);
            $not = true;
        }

        $this->match(TokenType::T_IN);
        $this->match(TokenType::T_OPEN_PARENTHESIS);

        if ($this->lexer->isNextToken(TokenType::T_SELECT)) {
            $inExpression = new AST\InSubselectExpression(
                $expression,
                $this->Subselect(),
                $not
            );
        } else {
            $literals = [$this->InParameter()];

            while ($this->lexer->isNextToken(TokenType::T_COMMA)) {
                $this->match(TokenType::T_COMMA);
                $literals[] = $this->InParameter();
            }

            $inExpression = new AST\InListExpression(
                $expression,
                $literals,
                $not
            );
        }

        $this->match(TokenType::T_CLOSE_PARENTHESIS);

        return $inExpression;
    }

    /**
     * InstanceOfExpression ::= IdentificationVariable ["NOT"] "INSTANCE" ["OF"] (InstanceOfParameter | "(" InstanceOfParameter {"," InstanceOfParameter}* ")")
     *
     * @return AST\InstanceOfExpression
     */
    public function InstanceOfExpression()
    {
        $identificationVariable = $this->IdentificationVariable();

        $not = false;
        if ($this->lexer->isNextToken(TokenType::T_NOT)) {
            $this->match(TokenType::T_NOT);
            $not = true;
        }

        $this->match(TokenType::T_INSTANCE);
        $this->match(TokenType::T_OF);

        $exprValues = $this->lexer->isNextToken(TokenType::T_OPEN_PARENTHESIS)
            ? $this->InstanceOfParameterList()
            : [$this->InstanceOfParameter()];

        return new AST\InstanceOfExpression(
            $identificationVariable,
            $exprValues,
            $not
        );
    }

    /** @return non-empty-list<AST\InputParameter|string> */
    public function InstanceOfParameterList(): array
    {
        $this->match(TokenType::T_OPEN_PARENTHESIS);

        $exprValues = [$this->InstanceOfParameter()];

        while ($this->lexer->isNextToken(TokenType::T_COMMA)) {
            $this->match(TokenType::T_COMMA);

            $exprValues[] = $this->InstanceOfParameter();
        }

        $this->match(TokenType::T_CLOSE_PARENTHESIS);

        return $exprValues;
    }

    /**
     * InstanceOfParameter ::= AbstractSchemaName | InputParameter
     *
     * @return AST\InputParameter|string
     */
    public function InstanceOfParameter()
    {
        if ($this->lexer->isNextToken(TokenType::T_INPUT_PARAMETER)) {
            $this->match(TokenType::T_INPUT_PARAMETER);
            assert($this->lexer->token !== null);

            return new AST\InputParameter($this->lexer->token->value);
        }

        $abstractSchemaName = $this->AbstractSchemaName();

        $this->validateAbstractSchemaName($abstractSchemaName);

        return $abstractSchemaName;
    }

    /**
     * LikeExpression ::= StringExpression ["NOT"] "LIKE" StringPrimary ["ESCAPE" char]
     *
     * @return AST\LikeExpression
     */
    public function LikeExpression()
    {
        $stringExpr = $this->StringExpression();
        $not        = false;

        if ($this->lexer->isNextToken(TokenType::T_NOT)) {
            $this->match(TokenType::T_NOT);
            $not = true;
        }

        $this->match(TokenType::T_LIKE);

        if ($this->lexer->isNextToken(TokenType::T_INPUT_PARAMETER)) {
            $this->match(TokenType::T_INPUT_PARAMETER);
            assert($this->lexer->token !== null);
            $stringPattern = new AST\InputParameter($this->lexer->token->value);
        } else {
            $stringPattern = $this->StringPrimary();
        }

        $escapeChar = null;

        if ($this->lexer->lookahead !== null && $this->lexer->lookahead->type === TokenType::T_ESCAPE) {
            $this->match(TokenType::T_ESCAPE);
            $this->match(TokenType::T_STRING);
            assert($this->lexer->token !== null);

            $escapeChar = new AST\Literal(AST\Literal::STRING, $this->lexer->token->value);
        }

        return new AST\LikeExpression($stringExpr, $stringPattern, $escapeChar, $not);
    }

    /**
     * NullComparisonExpression ::= (InputParameter | NullIfExpression | CoalesceExpression | AggregateExpression | FunctionDeclaration | IdentificationVariable | SingleValuedPathExpression | ResultVariable) "IS" ["NOT"] "NULL"
     *
     * @return AST\NullComparisonExpression
     */
    public function NullComparisonExpression()
    {
        switch (true) {
            case $this->lexer->isNextToken(TokenType::T_INPUT_PARAMETER):
                $this->match(TokenType::T_INPUT_PARAMETER);
                assert($this->lexer->token !== null);

                $expr = new AST\InputParameter($this->lexer->token->value);
                break;

            case $this->lexer->isNextToken(TokenType::T_NULLIF):
                $expr = $this->NullIfExpression();
                break;

            case $this->lexer->isNextToken(TokenType::T_COALESCE):
                $expr = $this->CoalesceExpression();
                break;

            case $this->isFunction():
                $expr = $this->FunctionDeclaration();
                break;

            default:
                // We need to check if we are in a IdentificationVariable or SingleValuedPathExpression
                $glimpse = $this->lexer->glimpse();
                assert($glimpse !== null);

                if ($glimpse->type === TokenType::T_DOT) {
                    $expr = $this->SingleValuedPathExpression();

                    // Leave switch statement
                    break;
                }

                assert($this->lexer->lookahead !== null);
                $lookaheadValue = $this->lexer->lookahead->value;

                // Validate existing component
                if (! isset($this->queryComponents[$lookaheadValue])) {
                    $this->semanticalError('Cannot add having condition on undefined result variable.');
                }

                // Validate SingleValuedPathExpression (ie.: "product")
                if (isset($this->queryComponents[$lookaheadValue]['metadata'])) {
                    $expr = $this->SingleValuedPathExpression();
                    break;
                }

                // Validating ResultVariable
                if (! isset($this->queryComponents[$lookaheadValue]['resultVariable'])) {
                    $this->semanticalError('Cannot add having condition on a non result variable.');
                }

                $expr = $this->ResultVariable();
                break;
        }

        $this->match(TokenType::T_IS);

        $not = false;
        if ($this->lexer->isNextToken(TokenType::T_NOT)) {
            $this->match(TokenType::T_NOT);

            $not = true;
        }

        $this->match(TokenType::T_NULL);

        return new AST\NullComparisonExpression($expr, $not);
    }

    /**
     * ExistsExpression ::= ["NOT"] "EXISTS" "(" Subselect ")"
     *
     * @return AST\ExistsExpression
     */
    public function ExistsExpression()
    {
        $not = false;

        if ($this->lexer->isNextToken(TokenType::T_NOT)) {
            $this->match(TokenType::T_NOT);
            $not = true;
        }

        $this->match(TokenType::T_EXISTS);
        $this->match(TokenType::T_OPEN_PARENTHESIS);

        $subselect = $this->Subselect();

        $this->match(TokenType::T_CLOSE_PARENTHESIS);

        return new AST\ExistsExpression($subselect, $not);
    }

    /**
     * ComparisonOperator ::= "=" | "<" | "<=" | "<>" | ">" | ">=" | "!="
     *
     * @return string
     */
    public function ComparisonOperator()
    {
        assert($this->lexer->lookahead !== null);
        switch ($this->lexer->lookahead->value) {
            case '=':
                $this->match(TokenType::T_EQUALS);

                return '=';

            case '<':
                $this->match(TokenType::T_LOWER_THAN);
                $operator = '<';

                if ($this->lexer->isNextToken(TokenType::T_EQUALS)) {
                    $this->match(TokenType::T_EQUALS);
                    $operator .= '=';
                } elseif ($this->lexer->isNextToken(TokenType::T_GREATER_THAN)) {
                    $this->match(TokenType::T_GREATER_THAN);
                    $operator .= '>';
                }

                return $operator;

            case '>':
                $this->match(TokenType::T_GREATER_THAN);
                $operator = '>';

                if ($this->lexer->isNextToken(TokenType::T_EQUALS)) {
                    $this->match(TokenType::T_EQUALS);
                    $operator .= '=';
                }

                return $operator;

            case '!':
                $this->match(TokenType::T_NEGATE);
                $this->match(TokenType::T_EQUALS);

                return '<>';

            default:
                $this->syntaxError('=, <, <=, <>, >, >=, !=');
        }
    }

    /**
     * FunctionDeclaration ::= FunctionsReturningStrings | FunctionsReturningNumerics | FunctionsReturningDatetime
     *
     * @return Functions\FunctionNode
     */
    public function FunctionDeclaration()
    {
        assert($this->lexer->lookahead !== null);
        $token    = $this->lexer->lookahead;
        $funcName = strtolower($token->value);

        $customFunctionDeclaration = $this->CustomFunctionDeclaration();

        // Check for custom functions functions first!
        switch (true) {
            case $customFunctionDeclaration !== null:
                return $customFunctionDeclaration;

            case isset(self::$stringFunctions[$funcName]):
                return $this->FunctionsReturningStrings();

            case isset(self::$numericFunctions[$funcName]):
                return $this->FunctionsReturningNumerics();

            case isset(self::$datetimeFunctions[$funcName]):
                return $this->FunctionsReturningDatetime();

            default:
                $this->syntaxError('known function', $token);
        }
    }

    /**
     * Helper function for FunctionDeclaration grammar rule.
     */
    private function CustomFunctionDeclaration(): ?Functions\FunctionNode
    {
        assert($this->lexer->lookahead !== null);
        $token    = $this->lexer->lookahead;
        $funcName = strtolower($token->value);

        // Check for custom functions afterwards
        $config = $this->em->getConfiguration();

        switch (true) {
            case $config->getCustomStringFunction($funcName) !== null:
                return $this->CustomFunctionsReturningStrings();

            case $config->getCustomNumericFunction($funcName) !== null:
                return $this->CustomFunctionsReturningNumerics();

            case $config->getCustomDatetimeFunction($funcName) !== null:
                return $this->CustomFunctionsReturningDatetime();

            default:
                return null;
        }
    }

    /**
     * FunctionsReturningNumerics ::=
     *      "LENGTH" "(" StringPrimary ")" |
     *      "LOCATE" "(" StringPrimary "," StringPrimary ["," SimpleArithmeticExpression]")" |
     *      "ABS" "(" SimpleArithmeticExpression ")" |
     *      "SQRT" "(" SimpleArithmeticExpression ")" |
     *      "MOD" "(" SimpleArithmeticExpression "," SimpleArithmeticExpression ")" |
     *      "SIZE" "(" CollectionValuedPathExpression ")" |
     *      "DATE_DIFF" "(" ArithmeticPrimary "," ArithmeticPrimary ")" |
     *      "BIT_AND" "(" ArithmeticPrimary "," ArithmeticPrimary ")" |
     *      "BIT_OR" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
     *
     * @return Functions\FunctionNode
     */
    public function FunctionsReturningNumerics()
    {
        assert($this->lexer->lookahead !== null);
        $funcNameLower = strtolower($this->lexer->lookahead->value);
        $funcClass     = self::$numericFunctions[$funcNameLower];

        $function = new $funcClass($funcNameLower);
        $function->parse($this);

        return $function;
    }

    /** @return Functions\FunctionNode */
    public function CustomFunctionsReturningNumerics()
    {
        assert($this->lexer->lookahead !== null);
        // getCustomNumericFunction is case-insensitive
        $functionName  = strtolower($this->lexer->lookahead->value);
        $functionClass = $this->em->getConfiguration()->getCustomNumericFunction($functionName);

        assert($functionClass !== null);

        $function = is_string($functionClass)
            ? new $functionClass($functionName)
            : $functionClass($functionName);

        $function->parse($this);

        return $function;
    }

    /**
     * FunctionsReturningDateTime ::=
     *     "CURRENT_DATE" |
     *     "CURRENT_TIME" |
     *     "CURRENT_TIMESTAMP" |
     *     "DATE_ADD" "(" ArithmeticPrimary "," ArithmeticPrimary "," StringPrimary ")" |
     *     "DATE_SUB" "(" ArithmeticPrimary "," ArithmeticPrimary "," StringPrimary ")"
     *
     * @return Functions\FunctionNode
     */
    public function FunctionsReturningDatetime()
    {
        assert($this->lexer->lookahead !== null);
        $funcNameLower = strtolower($this->lexer->lookahead->value);
        $funcClass     = self::$datetimeFunctions[$funcNameLower];

        $function = new $funcClass($funcNameLower);
        $function->parse($this);

        return $function;
    }

    /** @return Functions\FunctionNode */
    public function CustomFunctionsReturningDatetime()
    {
        assert($this->lexer->lookahead !== null);
        // getCustomDatetimeFunction is case-insensitive
        $functionName  = $this->lexer->lookahead->value;
        $functionClass = $this->em->getConfiguration()->getCustomDatetimeFunction($functionName);

        assert($functionClass !== null);

        $function = is_string($functionClass)
            ? new $functionClass($functionName)
            : $functionClass($functionName);

        $function->parse($this);

        return $function;
    }

    /**
     * FunctionsReturningStrings ::=
     *   "CONCAT" "(" StringPrimary "," StringPrimary {"," StringPrimary}* ")" |
     *   "SUBSTRING" "(" StringPrimary "," SimpleArithmeticExpression "," SimpleArithmeticExpression ")" |
     *   "TRIM" "(" [["LEADING" | "TRAILING" | "BOTH"] [char] "FROM"] StringPrimary ")" |
     *   "LOWER" "(" StringPrimary ")" |
     *   "UPPER" "(" StringPrimary ")" |
     *   "IDENTITY" "(" SingleValuedAssociationPathExpression {"," string} ")"
     *
     * @return Functions\FunctionNode
     */
    public function FunctionsReturningStrings()
    {
        assert($this->lexer->lookahead !== null);
        $funcNameLower = strtolower($this->lexer->lookahead->value);
        $funcClass     = self::$stringFunctions[$funcNameLower];

        $function = new $funcClass($funcNameLower);
        $function->parse($this);

        return $function;
    }

    /** @return Functions\FunctionNode */
    public function CustomFunctionsReturningStrings()
    {
        assert($this->lexer->lookahead !== null);
        // getCustomStringFunction is case-insensitive
        $functionName  = $this->lexer->lookahead->value;
        $functionClass = $this->em->getConfiguration()->getCustomStringFunction($functionName);

        assert($functionClass !== null);

        $function = is_string($functionClass)
            ? new $functionClass($functionName)
            : $functionClass($functionName);

        $function->parse($this);

        return $function;
    }

    private function getMetadataForDqlAlias(string $dqlAlias): ClassMetadata
    {
        if (! isset($this->queryComponents[$dqlAlias]['metadata'])) {
            throw new LogicException(sprintf('No metadata for DQL alias: %s', $dqlAlias));
        }

        return $this->queryComponents[$dqlAlias]['metadata'];
    }
}
