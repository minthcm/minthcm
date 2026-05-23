<?php

namespace MintHCM\Lib\MintLogic\Formulas;

use MintHCM\Lib\MintLogic\Formula;

class _Or extends Formula
{
    public function execute(...$args)
    {
        foreach ($args as $arg) {
            if (boolval(self::executeOperator($arg['op'], $this->bean, ...$arg['args']))) {
                return true;
            }
        }
        return false;
    }
}
