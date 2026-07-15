<?php
namespace MintHCM\AI\Tools\Exceptions;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class ModuleNotAllowedException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct('ModuleNotAllowedException - ' . $message);
    }
}
