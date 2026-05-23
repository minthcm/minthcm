<?php

namespace MintHCM\MintCLI\Questions;

use MintHCM\MintCLI\InputValidators\NumericalValidator;

class DatabasePort extends Question
{
    protected $question = "Database Port";
    protected $defaultValue = "3306";

    public function __construct($qh, $input, $output)
    {
        parent::__construct($qh, $input, $output);
        $this->validators = [
            new NumericalValidator(),
        ];
    }
}
