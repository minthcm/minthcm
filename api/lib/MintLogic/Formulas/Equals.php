<?php

namespace MintHCM\Lib\MintLogic\Formulas;

use MintHCM\Lib\MintLogic\Formula;

class Equals extends Formula
{
    public function execute(...$args)
    {
        return $args[0] == $args[1];
    }
}
