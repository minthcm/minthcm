<?php

namespace MintHCM\Lib\MintLogic\Formulas;

use MintHCM\Lib\MintLogic\Formula;

class _Not extends Formula
{
    public function execute($arg)
    {
        return !boolval(self::executeOperator($arg['op'], $this->bean, ...$arg['args']));
    }
}
