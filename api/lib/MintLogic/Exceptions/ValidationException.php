<?php

namespace MintHCM\Lib\MintLogic\Exceptions;

class ValidationException extends \Exception
{
    protected $message = 'ERR_VALIDATION';
    protected $code = 422;

    public function __construct($message = null, $code = null)
    {
        if ($message) {
            $this->message = $message;
        }
        if ($code) {
            $this->code = $code;
        }
    }
}
