<?php

namespace MintHCM\Lib\MintLogic;

abstract class Validator
{
    public abstract function validate($bean, $field = null);
}
