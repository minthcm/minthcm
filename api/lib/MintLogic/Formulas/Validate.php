<?php

namespace MintHCM\Lib\MintLogic\Formulas;

use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Formula;

class Validate extends Formula
{
    public function execute($expr, $message)
    {
        if (boolval(self::executeOperator($expr['op'], $this->bean, ...$expr['args']))) {
            throw new ValidationException($message);
        }
        return true;
    }
}
