<?php

namespace MintHCM\MintCLI\Questions;

use Symfony\Component\Console\Question\ConfirmationQuestion as BasicConfirmationQuestion;

class RebuildFrontend extends Question
{
    protected $question = "Rebuild Frontend (requires Node.js v16.04)";
    protected $defaultValue = false;
    protected $defaultDisplayValue = 'no';

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
