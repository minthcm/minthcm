<?php

namespace MintHCM\MintCLI\Questions;

use MintHCM\MintCLI\InputValidators\NoWhitespaceValidator;
use MintHCM\MintCLI\InputValidators\YesNoValidator;
use Symfony\Component\Console\Question\ConfirmationQuestion as BasicConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConfirmationQuestion extends Question
{
    protected $defaultDisplayValue;

    public function ask()
    {
        $this->question = $this->question . " (yes/no)";
        if (isset($this->defaultValue)) {
            $this->question = $this->question . " [" . $this->defaultDisplayValue . "]";
        }
        $this->question = $this->question . ": ";
        $question = new BasicConfirmationQuestion($this->question, $this->defaultValue);

        return $this->qh->ask($this->input, $this->output, $question);
    }

}
