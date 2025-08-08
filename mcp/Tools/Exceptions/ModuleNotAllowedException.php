<?php

namespace MintMCP\Tools\Exceptions;

class ModuleNotAllowedException extends \Exception
{

    public function __construct(string $message)
    {
        parent::__construct('ModuleNotAllowedException - ' . $message);
    }
}