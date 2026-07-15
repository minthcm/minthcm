<?php

namespace MintMCP\Capabilities\Resources;

abstract class AbstractMCPAppResource
{
    protected static function appDistPath(string $appName): string
    {
        return __DIR__ . '/../../apps/dist/' . $appName . '.html';
    }
}
