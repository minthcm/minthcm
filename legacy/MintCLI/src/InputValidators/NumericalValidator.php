<?php

namespace MintHCM\MintCLI\InputValidators;

#[\AllowDynamicProperties]
class NumericalValidator extends RegExpValidator
{
    protected $pattern = "/^\d+$/";
    protected $message = "Invalid value - The value cannot contain characters other then numbers.";
}
