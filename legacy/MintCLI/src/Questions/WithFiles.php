<?php

namespace MintHCM\MintCLI\Questions;

class WithFiles extends ConfirmationQuestion
{
    protected $question = "Dump files?";
    protected $defaultValue = false;
    protected $defaultDisplayValue = 'no';
}
