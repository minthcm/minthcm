<?php

namespace MintHCM\Lib\MintLogic\Formulas;

use MintHCM\Lib\MintLogic\Formula;

class _Empty extends Formula
{
    public function execute($arg)
    {
        return empty($arg);
    }
}
