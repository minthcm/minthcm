<?php

namespace MintHCM\MintCLI\Questions;

class SSL extends ConfirmationQuestion
{
    protected $question = "SSL";
    protected $defaultValue = false;
    protected $defaultDisplayValue = 'no';
}
