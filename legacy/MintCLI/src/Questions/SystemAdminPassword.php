<?php

namespace MintHCM\MintCLI\Questions;

use MintHCM\MintCLI\InputValidators\NotEmptyValidator;
use MintHCM\MintCLI\InputValidators\NoWhitespaceValidator;

class SystemAdminPassword extends Question
{
    protected $question = "System Administrator Password";    
    protected $defaultValue = null;

    public function __construct($qh, $input, $output)
    {
        parent::__construct($qh, $input, $output);
        $this->validators = [
            new NotEmptyValidator(),
            new NoWhitespaceValidator(),
        ];
    }
}
