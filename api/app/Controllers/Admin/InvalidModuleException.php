<?php

namespace MintHCM\Api\Controllers\Admin;

class InvalidModuleException extends \RuntimeException
{
    private array $errorData;

    public function __construct(array $errorData)
    {
        parent::__construct($errorData['message'] ?? 'Invalid module');
        $this->errorData = $errorData;
    }

    public function getErrorData(): array
    {
        return $this->errorData;
    }
}
