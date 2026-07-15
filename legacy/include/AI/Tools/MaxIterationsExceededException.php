<?php

namespace MintHCM\AI\Tools;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class MaxIterationsExceededException extends \RuntimeException
{
    public function __construct(public readonly int $limit)
    {
        parent::__construct('Agent exceeded the maximum of ' . $limit . ' iterations.');
    }
}
