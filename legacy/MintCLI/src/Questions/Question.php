<?php

namespace MintHCM\MintCLI\Questions;

use MintHCM\MintCLI\InputValidators\NoWhitespaceValidator;
use Symfony\Component\Console\Question\Question as BasicQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class Question
{
    protected $io;
    protected $qh;
    protected $input;
    protected $output;
    protected $question;
    protected $defaultValue;
    protected $validators = [];
    protected $validationError;

    public function __construct($qh, $input, $output)
    {
        $this->qh = $qh;
        $this->input = $input;
        $this->output = $output;
        $this->io = new SymfonyStyle($input, $output);
        $this->validators = [
            new NoWhitespaceValidator(),
        ];
    }

    public function ask()
    {
        if (isset($this->defaultValue)) {
            $this->question = $this->question . " [" . $this->defaultValue . "]";
        }
        $this->question = $this->question . ": ";
        $question = new BasicQuestion($this->question, $this->defaultValue);

        do {
            $answer = $this->qh->ask($this->input, $this->output, $question);
            $validAnswer = $this->validateAnswer($answer);
            if (!$validAnswer) {
                $this->io->error($this->validationError);
            }
        } while (!$validAnswer);

        return $answer;
    }

    public function setDefaultValue($value)
    {
        $this->defaultValue = $value;
    }

    protected function validateAnswer($answer)
    {
        if (!empty($this->validators)) {
            foreach ($this->validators as $validator) {
                if (!$validator->validate($answer)) {
                    $this->validationError = $validator->getMessage();
                    return false;
                }
            }
        }
        return true;
    }
}
