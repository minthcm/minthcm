<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpCsFixer\Tests\Test;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\AbstractProxyFixer;
use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use PhpCsFixer\Fixer\ConfigurationDefinitionFixerInterface;
use PhpCsFixer\Fixer\DefinedFixerInterface;
use PhpCsFixer\Fixer\DeprecatedFixerInterface;
use PhpCsFixer\Fixer\Whitespace\SingleBlankLineAtEofFixer;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;
use PhpCsFixer\FixerDefinition\CodeSampleInterface;
use PhpCsFixer\FixerDefinition\FileSpecificCodeSampleInterface;
use PhpCsFixer\FixerDefinition\VersionSpecificCodeSampleInterface;
use PhpCsFixer\Linter\CachingLinter;
use PhpCsFixer\Linter\Linter;
use PhpCsFixer\Linter\LinterInterface;
use PhpCsFixer\Linter\ProcessLinter;
use PhpCsFixer\Preg;
use PhpCsFixer\StdinFileInfo;
use PhpCsFixer\Tests\Test\Assert\AssertTokensTrait;
use PhpCsFixer\Tests\TestCase;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use Prophecy\Argument;

/**
 * @author Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * @internal
 */
abstract class AbstractFixerTestCase extends TestCase
{
    use AssertTokensTrait;
    use IsIdenticalConstraint;

    /**
     * @var null|LinterInterface
     */
    protected $linter;

    /**
     * @var null|AbstractFixer
     */
    protected $fixer;

    // do not modify this structure without prior discussion
    private $allowedRequiredOptions = [
        'header_comment' => ['header' => true],
    ];

    // do not modify this structure without prior discussion
    private $allowedFixersWithoutDefaultCodeSample = [
        'general_phpdoc_annotation_remove' => true,
        'general_phpdoc_tag_rename' => true,
    ];

    protected function doSetUp()
    {
        parent::doSetUp();

        $this->linter = $this->getLinter();
        $this->fixer = $this->createFixer();

        // @todo remove at 3.0 together with env var itself
        if (getenv('PHP_CS_FIXER_TEST_USE_LEGACY_TOKENIZER')) {
            Tokens::setLegacyMode(true);
        }
    }

    protected function doTearDown()
    {
        parent::doTearDown();

        $this->linter = null;
        $this->fixer = null;

        // @todo remove at 3.0
        Tokens::setLegacyMode(false);
    }

    final public function testIsRisky()
    {
        static::assertIsBool($this->fixer->isRisky(), sprintf('Return type for ::isRisky of "%s" is invalid.', $this->fixer->getName()));

        if ($this->fixer->isRisky()) {
            self::assertValidDescription($this->fixer->getName(), 'risky description', $this->fixer->getDefinition()->getRiskyDescription());
        } else {
            static::assertNull($this->fixer->getDefinition()->getRiskyDescription(), sprintf('[%s] Fixer is not risky so no description of it expected.', $this->fixer->getName()));
        }

        if ($this->fixer instanceof AbstractProxyFixer) {
            return;
        }

        $reflection = new \ReflectionMethod($this->fixer, 'isRisky');

        // If fixer is not risky then the method `isRisky` from `AbstractFixer` must be used
        static::assertSame(
            !$this->fixer->isRisky(),
            AbstractFixer::class === $reflection->getDeclaringClass()->getName()
        );
    }

    final public function testFixerDefinitions()
    {
        static::assertInstanceOf(DefinedFixerInterface::class, $this->fixer);

        $fixerName = $this->fixer->getName();
        $definition = $this->fixer->getDefinition();
        $fixerIsConfigurable = $this->fixer instanceof ConfigurationDefinitionFixerInterface;

        self::assertValidDescription($fixerName, 'summary', $definition->getSummary());

        $samples = $definition->getCodeSamples();
        static::assertNotEmpty($samples, sprintf('[%s] Code samples are required.', $fixerName));

        $configSamplesProvided = [];
        $dummyFileInfo = new StdinFileInfo();
        foreach ($samples as $sampleCounter => $sample) {
            static::assertInstanceOf(CodeSampleInterface::class, $sample, sprintf('[%s] Sample #%d', $fixerName, $sampleCounter));
            static::assertIsInt($sampleCounter);

            $code = $sample->getCode();

            static::assertIsString($code, sprintf('[%s] Sample #%d', $fixerName, $sampleCounter));
            static::assertNotEmpty($code, sprintf('[%s] Sample #%d', $fixerName, $sampleCounter));

            if (!($this->fixer instanceof SingleBlankLineAtEofFixer)) {
                static::assertSame("\n", substr($code, -1), sprintf('[%s] Sample #%d must end with linebreak', $fixerName, $sampleCounter));
            }

            $config = $sample->getConfiguration();
            if (null !== $config) {
                static::assertTrue($fixerIsConfigurable, sprintf('[%s] Sample #%d has configuration, but the fixer is not configurable.', $fixerName, $sampleCounter));
                static::assertIsArray($config, sprintf('[%s] Sample #%d configuration must be an array or null.', $fixerName, $sampleCounter));

                $configSamplesProvided[$sampleCounter] = $config;
            } elseif ($fixerIsConfigurable) {
                if (!$sample instanceof VersionSpecificCodeSampleInterface) {
                    static::assertArrayNotHasKey('default', $configSamplesProvided, sprintf('[%s] Multiple non-versioned samples with default configuration.', $fixerName));
                }

                $configSamplesProvided['default'] = true;
            }

            if ($sample instanceof VersionSpecificCodeSampleInterface && !$sample->isSuitableFor(\PHP_VERSION_ID)) {
                continue;
            }

            if ($fixerIsConfigurable) {
                // always re-configure as the fixer might have been configured with diff. configuration form previous sample
                $this->fixer->configure(null === $config ? [] : $config);
            }

            Tokens::clearCache();
            $tokens = Tokens::fromCode($code);
            $this->fixer->fix(
                $sample instanceof FileSpecificCodeSampleInterface ? $sample->getSplFileInfo() : $dummyFileInfo,
                $tokens
            );

            static::assertTrue($tokens->isChanged(), sprintf('[%s] Sample #%d is not changed during fixing.', $fixerName, $sampleCounter));

            $duplicatedCodeSample = array_search(
                $sample,
                \array_slice($samples, 0, $sampleCounter),
                false
            );

            static::assertFalse(
                $duplicatedCodeSample,
                sprintf('[%s] Sample #%d duplicates #%d.', $fixerName, $sampleCounter, $duplicatedCodeSample)
            );
        }

        if ($fixerIsConfigurable) {
            if (isset($configSamplesProvided['default'])) {
                reset($configSamplesProvided);
                static::assertSame('default', key($configSamplesProvided), sprintf('[%s] First sample must be for the default configuration.', $fixerName));
            } elseif (!isset($this->allowedFixersWithoutDefaultCodeSample[$fixerName])) {
                static::assertArrayHasKey($fixerName, $this->allowedRequiredOptions, sprintf('[%s] Has no sample for default configuration.', $fixerName));
            }

            // It may only shrink, never add anything to it.
            $fixerNamesWithKnownMissingSamplesWithConfig = [ // @TODO 3.0 - remove this
                'is_null', // has only one option which is deprecated
            ];

            if (\count($configSamplesProvided) < 2) {
                if (\in_array($fixerName, $fixerNamesWithKnownMissingSamplesWithConfig, true)) {
                    static::markTestIncomplete(sprintf('[%s] Configurable fixer only provides a default configuration sample and none for its configuration options, please help and add it.', $fixerName));
                }

                static::fail(sprintf('[%s] Configurable fixer only provides a default configuration sample and none for its configuration options.', $fixerName));
            } elseif (\in_array($fixerName, $fixerNamesWithKnownMissingSamplesWithConfig, true)) {
                static::fail(sprintf('[%s] Invalid listed as missing code samples, please update the list.', $fixerName));
            }

            $options = $this->fixer->getConfigurationDefinition()->getOptions();

            foreach ($options as $option) {
                static::assertMatchesRegularExpression('/^[a-z_]+[a-z]$/', $option->getName(), sprintf('[%s] Option %s is not snake_case.', $fixerName, $option->getName()));
            }
        }
    }

    /**
     * @group legacy
     */
    final public function testLegacyFixerDefinitions()
    {
        $this->expectDeprecation('PhpCsFixer\\FixerDefinition\\FixerDefinition::getConfigurationDescription is deprecated and will be removed in 3.0.');
        $this->expectDeprecation('PhpCsFixer\\FixerDefinition\\FixerDefinition::getDefaultConfiguration is deprecated and will be removed in 3.0.');
        $definition = $this->fixer->getDefinition();

        static::assertNull($definition->getConfigurationDescription(), sprintf('[%s] No configuration description expected.', $this->fixer->getName()));
        static::assertNull($definition->getDefaultConfiguration(), sprintf('[%s] No default configuration expected.', $this->fixer->getName()));
    }

    final public function testFixersAreFinal()
    {
        $reflection = new \ReflectionClass($this->fixer);

        static::assertTrue(
            $reflection->isFinal(),
            sprintf('Fixer "%s" must be declared "final".', $this->fixer->getName())
        );
    }

    final public function testFixersAreDefined()
    {
        static::assertInstanceOf(DefinedFixerInterface::class, $this->fixer);
    }

    final public function testDeprecatedFixersHaveCorrectSummary()
    {
        $reflection = new \ReflectionClass($this->fixer);
        $comment = $reflection->getDocComment();

        static::assertStringNotContainsString(
            'DEPRECATED',
            $this->fixer->getDefinition()->getSummary(),
            'Fixer cannot contain word "DEPRECATED" in summary'
        );

        if ($this->fixer instanceof DeprecatedFixerInterface) {
            static::assertStringContainsString('@deprecated', $comment);
        } elseif (\is_string($comment)) {
            static::assertStringNotContainsString('@deprecated', $comment);
        }
    }

    /**
     * Blur filter that find candidate fixer for performance optimization to use only `insertSlices` instead of multiple `insertAt` if there is no other collection manipulation.
     */
    public function testFixerUseInsertSlicesWhenOnlyInsertionsArePerformed()
    {
        $reflection = new \ReflectionClass($this->fixer);
        $tokens = Tokens::fromCode(file_get_contents($reflection->getFileName()));

        $sequences = $this->findAllTokenSequences($tokens, [[T_VARIABLE, '$tokens'], [T_OBJECT_OPERATOR], [T_STRING]]);

        $usedMethods = array_unique(array_map(function (array $sequence) {
            $last = end($sequence);

            return $last->getContent();
        }, $sequences));

        // if there is no `insertAt`, it's not a candidate
        if (!\in_array('insertAt', $usedMethods, true)) {
            $this->addToAssertionCount(1);

            return;
        }

        $usedMethods = array_filter($usedMethods, function ($method) {
            return 0 === Preg::match('/^(count|find|generate|get|is|rewind)/', $method);
        });

        $allowedMethods = ['insertAt'];
        $nonAllowedMethods = array_diff($usedMethods, $allowedMethods);

        if ([] === $nonAllowedMethods) {
            $fixerName = $this->fixer->getName();
            if (\in_array($fixerName, [
                // DO NOT add anything to this list at ease, align with core contributors whether it makes sense to insert tokens individually or by bulk for your case.
                // The original list of the fixers being exceptions and insert tokens individually came from legacy reasons when it was the only available methods to insert tokens.
                'blank_line_after_namespace',
                'blank_line_after_opening_tag',
                'blank_line_before_statement',
                'class_attributes_separation',
                'date_time_immutable',
                'declare_strict_types',
                'doctrine_annotation_braces',
                'doctrine_annotation_spaces',
                'final_internal_class',
                'final_public_method_for_abstract_class',
                'function_typehint_space',
                'heredoc_indentation',
                'method_chaining_indentation',
                'native_constant_invocation',
                'new_with_braces',
                'no_short_echo_tag',
                'not_operator_with_space',
                'not_operator_with_successor_space',
                'php_unit_internal_class',
                'php_unit_no_expectation_annotation',
                'php_unit_set_up_tear_down_visibility',
                'php_unit_size_class',
                'php_unit_test_annotation',
                'php_unit_test_class_requires_covers',
                'phpdoc_to_param_type',
                'phpdoc_to_property_type',
                'phpdoc_to_return_type',
                'random_api_migration',
                'semicolon_after_instruction',
                'single_line_after_imports',
                'static_lambda',
                'strict_param',
                'void_return',
            ], true)) {
                static::markTestIncomplete(sprintf('Fixer "%s" may be optimized to use `Tokens::insertSlices` instead of `%s`, please help and optimize it.', $fixerName, implode(', ', $allowedMethods)));
            }
            static::fail(sprintf('Fixer "%s" shall be optimized to use `Tokens::insertSlices` instead of `%s`.', $fixerName, implode(', ', $allowedMethods)));
        }

        $this->addToAssertionCount(1);
    }

    final public function testFixerConfigurationDefinitions()
    {
        if (!$this->fixer instanceof ConfigurationDefinitionFixerInterface) {
            $this->addToAssertionCount(1); // not applied to the fixer without configuration

            return;
        }

        $configurationDefinition = $this->fixer->getConfigurationDefinition();

        static::assertInstanceOf(FixerConfigurationResolverInterface::class, $configurationDefinition);

        foreach ($configurationDefinition->getOptions() as $option) {
            static::assertInstanceOf(FixerOptionInterface::class, $option);
            static::assertNotEmpty($option->getDescription());

            static::assertSame(
                !isset($this->allowedRequiredOptions[$this->fixer->getName()][$option->getName()]),
                $option->hasDefault(),
                sprintf(
                    $option->hasDefault()
                        ? 'Option `%s` of fixer `%s` is wrongly listed in `$allowedRequiredOptions` structure, as it is not required. If you just changed that option to not be required anymore, please adjust mentioned structure.'
                        : 'Option `%s` of fixer `%s` shall not be required. If you want to introduce new required option please adjust `$allowedRequiredOptions` structure.',
                    $option->getName(),
                    $this->fixer->getName()
                )
            );

            static::assertStringNotContainsString(
                'DEPRECATED',
                $option->getDescription(),
                'Option description cannot contain word "DEPRECATED"'
            );
        }
    }

    final public function testFixersReturnTypes()
    {
        $tokens = Tokens::fromCode('<?php ');
        $emptyTokens = new Tokens();

        static::assertIsInt($this->fixer->getPriority(), sprintf('Return type for ::getPriority of "%s" is invalid.', $this->fixer->getName()));
        static::assertIsBool($this->fixer->supports(new \SplFileInfo(__FILE__)), sprintf('Return type for ::supports of "%s" is invalid.', $this->fixer->getName()));

        static::assertIsBool($this->fixer->isCandidate($emptyTokens), sprintf('Return type for ::isCandidate with empty tokens of "%s" is invalid.', $this->fixer->getName()));
        static::assertFalse($emptyTokens->isChanged());

        static::assertIsBool($this->fixer->isCandidate($tokens), sprintf('Return type for ::isCandidate of "%s" is invalid.', $this->fixer->getName()));
        static::assertFalse($tokens->isChanged());

        if ($this->fixer instanceof HeaderCommentFixer) {
            $this->fixer->configure(['header' => 'a']);
        }

        static::assertNull($this->fixer->fix(new \SplFileInfo(__FILE__), $emptyTokens), sprintf('Return type for ::fix with empty tokens of "%s" is invalid.', $this->fixer->getName()));
        static::assertFalse($emptyTokens->isChanged());

        static::assertNull($this->fixer->fix(new \SplFileInfo(__FILE__), $tokens), sprintf('Return type for ::fix of "%s" is invalid.', $this->fixer->getName()));
    }

    /**
     * @return AbstractFixer
     */
    protected function createFixer()
    {
        $fixerClassName = preg_replace('/^(PhpCsFixer)\\\\Tests(\\\\.+)Test$/', '$1$2', static::class);

        return new $fixerClassName();
    }

    /**
     * @param string $filename
     *
     * @return \SplFileInfo
     */
    protected function getTestFile($filename = __FILE__)
    {
        static $files = [];

        if (!isset($files[$filename])) {
            $files[$filename] = new \SplFileInfo($filename);
        }

        return $files[$filename];
    }

    /**
     * Tests if a fixer fixes a given string to match the expected result.
     *
     * It is used both if you want to test if something is fixed or if it is not touched by the fixer.
     * It also makes sure that the expected output does not change when run through the fixer. That means that you
     * do not need two test cases like [$expected] and [$expected, $input] (where $expected is the same in both cases)
     * as the latter covers both of them.
     * This method throws an exception if $expected and $input are equal to prevent test cases that accidentally do
     * not test anything.
     *
     * @param string            $expected The expected fixer output
     * @param null|string       $input    The fixer input, or null if it should intentionally be equal to the output
     * @param null|\SplFileInfo $file     The file to fix, or null if unneeded
     */
    protected function doTest($expected, $input = null, \SplFileInfo $file = null)
    {
        if ($expected === $input) {
            throw new \InvalidArgumentException('Input parameter must not be equal to expected parameter.');
        }

        $file = $file ?: $this->getTestFile();
        $fileIsSupported = $this->fixer->supports($file);

        if (null !== $input) {
            static::assertNull($this->lintSource($input));

            Tokens::clearCache();
            $tokens = Tokens::fromCode($input);

            if ($fileIsSupported) {
                static::assertTrue($this->fixer->isCandidate($tokens), 'Fixer must be a candidate for input code.');
                static::assertFalse($tokens->isChanged(), 'Fixer must not touch Tokens on candidate check.');
                $fixResult = $this->fixer->fix($file, $tokens);
                static::assertNull($fixResult, '->fix method must return null.');
            }

            static::assertThat(
                $tokens->generateCode(),
                self::createIsIdenticalStringConstraint($expected),
                'Code build on input code must match expected code.'
            );
            static::assertTrue($tokens->isChanged(), 'Tokens collection built on input code must be marked as changed after fixing.');

            $tokens->clearEmptyTokens();

            static::assertSame(
                \count($tokens),
                \count(array_unique(array_map(static function (Token $token) {
                    return spl_object_hash($token);
                }, $tokens->toArray()))),
                'Token items inside Tokens collection must be unique.'
            );

            Tokens::clearCache();
            $expectedTokens = Tokens::fromCode($expected);
            static::assertTokens($expectedTokens, $tokens);
        }

        static::assertNull($this->lintSource($expected));

        Tokens::clearCache();
        $tokens = Tokens::fromCode($expected);

        if ($fileIsSupported) {
            $fixResult = $this->fixer->fix($file, $tokens);
            static::assertNull($fixResult, '->fix method must return null.');
        }

        static::assertThat(
            $tokens->generateCode(),
            self::createIsIdenticalStringConstraint($expected),
            'Code build on expected code must not change.'
        );
        static::assertFalse($tokens->isChanged(), 'Tokens collection built on expected code must not be marked as changed after fixing.');
    }

    /**
     * @param string $source
     *
     * @return null|string
     */
    protected function lintSource($source)
    {
        try {
            $this->linter->lintSource($source)->check();
        } catch (\Exception $e) {
            return $e->getMessage()."\n\nSource:\n{$source}";
        }

        return null;
    }

    /**
     * @return LinterInterface
     */
    private function getLinter()
    {
        static $linter = null;

        if (null === $linter) {
            if (getenv('SKIP_LINT_TEST_CASES')) {
                $linterProphecy = $this->prophesize(\PhpCsFixer\Linter\LinterInterface::class);
                $linterProphecy
                    ->lintSource(Argument::type('string'))
                    ->willReturn($this->prophesize(\PhpCsFixer\Linter\LintingResultInterface::class)->reveal())
                ;

                $linter = $linterProphecy->reveal();
            } else {
                $linter = new CachingLinter(
                    getenv('FAST_LINT_TEST_CASES') ? new Linter() : new ProcessLinter()
                );
            }
        }

        return $linter;
    }

    /**
     * @param string $fixerName
     * @param string $descriptionType
     * @param mixed  $description
     */
    private static function assertValidDescription($fixerName, $descriptionType, $description)
    {
        static::assertIsString($description);
        static::assertMatchesRegularExpression('/^[A-Z`][^"]+\.$/', $description, sprintf('[%s] The %s must start with capital letter or a ` and end with dot.', $fixerName, $descriptionType));
        static::assertStringNotContainsString('phpdocs', $description, sprintf('[%s] `PHPDoc` must not be in the plural in %s.', $fixerName, $descriptionType));
        static::assertCorrectCasing($description, 'PHPDoc', sprintf('[%s] `PHPDoc` must be in correct casing in %s.', $fixerName, $descriptionType));
        static::assertCorrectCasing($description, 'PHPUnit', sprintf('[%s] `PHPUnit` must be in correct casing in %s.', $fixerName, $descriptionType));
        static::assertFalse(strpos($descriptionType, '``'), sprintf('[%s] The %s must no contain sequential backticks.', $fixerName, $descriptionType));
    }

    /**
     * @param string $needle
     * @param string $haystack
     * @param string $message
     */
    private static function assertCorrectCasing($needle, $haystack, $message)
    {
        static::assertSame(substr_count(strtolower($haystack), strtolower($needle)), substr_count($haystack, $needle), $message);
    }

    private function findAllTokenSequences($tokens, $sequence)
    {
        $lastIndex = 0;
        $sequences = [];
        while ($found = $tokens->findSequence($sequence, $lastIndex)) {
            $keys = array_keys($found);
            $sequences[] = $found;
            $lastIndex = $keys[2];
        }

        return $sequences;
    }
}
