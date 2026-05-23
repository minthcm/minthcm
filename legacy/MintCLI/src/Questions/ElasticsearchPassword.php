<?php

namespace MintHCM\MintCLI\Questions;

use MintHCM\MintCLI\InputValidators\NotEmptyValidator;
use MintHCM\MintCLI\InputValidators\NoWhitespaceValidator;

class ElasticsearchPassword extends Question
{
    protected $question = "Elasticsearch Password";
    protected $defaultValue = "changeme";

    public function __construct($qh, $input, $output)
    {
        parent::__construct($qh, $input, $output);
        $this->validators = [
            new NotEmptyValidator(),
            new NoWhitespaceValidator(),
        ];
    }
}
