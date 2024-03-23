<?php

namespace MintHCM\MintCLI\InputValidators;

class NoWhitespaceValidator extends RegExpValidator
{
    protected $pattern = "/^[^\s]+$/";
    protected $message = "Invalid value - The value cannot contain illegal whitespace characters (ie. spaces, tabulations, etc.).";
}
