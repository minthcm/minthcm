<?php

declare(strict_types=1);

namespace Doctrine\ORM\Query\Expr;

/**
 * Expression class for generating DQL functions.
 *
 * @link    www.doctrine-project.org
 */
class Literal extends Base
{
    /** @var string */
    protected $preSeparator = '';

    /** @var string */
    protected $postSeparator = '';

    /** @phpstan-var list<string> */
    protected $parts = [];

    /** @phpstan-return list<string> */
    public function getParts()
    {
        return $this->parts;
    }
}
