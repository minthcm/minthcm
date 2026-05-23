<?php

namespace MintHCM\MintCLI\Questions;
use MintHCM\MintCLI\InputValidators\NotEmptyValidator;

class ModuleName extends Question
{
    protected $question = "Module Name";
    protected $defaultValue = null;
    
    public function __construct($qh, $input, $output)
    {
        parent::__construct($qh, $input, $output);
        $this->validators = [
            new NotEmptyValidator()
        ];
    }
}
