<?php

namespace MintHCM\AI\Tools;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

readonly class ToolResult
{
    public function __construct(
        public bool    $success,
        public string  $output,
        public ?string $error = null,
        /** @var array<string, mixed>|null */
        public ?array  $structured_content = null,
    ) {}

    public static function ok(string $output): self
    {
        return new self(true, $output, null);
    }

    public static function fail(string $error, string $output = ''): self
    {
        return new self(false, $output !== '' ? $output : $error, $error);
    }
}
