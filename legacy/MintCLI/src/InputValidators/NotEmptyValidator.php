<?php

namespace MintHCM\MintCLI\InputValidators;

class NotEmptyValidator extends Validator
{
    protected $message = "Invalid value - The value cannot be empty.";

    public function validate($value)
    {
        return isset($value);
    }
}
