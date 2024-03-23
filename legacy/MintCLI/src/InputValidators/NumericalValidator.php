<?php

namespace MintHCM\MintCLI\InputValidators;

class NumericalValidator extends RegExpValidator
{
    protected $pattern = "/^\d+$/";
    protected $message = "Invalid value - The value cannot contain characters other then numbers.";
}
