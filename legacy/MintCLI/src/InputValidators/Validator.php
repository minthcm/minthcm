<?php

namespace MintHCM\MintCLI\InputValidators;

abstract class Validator
{
    protected $message;

    public function getMessage()
    {
        return $this->message;
    }

    abstract public function validate($value);
}
