<?php

namespace MintHCM\MintCLI\InputValidators;

abstract class RegExpValidator extends Validator
{
    protected $pattern = "/./";

    public function validate($value)
    {
        if(preg_match($this->pattern, $value)) {
            return true;
        }
        return false;
    }   
}
