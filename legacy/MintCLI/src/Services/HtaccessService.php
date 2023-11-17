<?php

namespace MintHCM\MintCLI\Services;

class HtaccessService
{
    const INSTANCE_DIR = './legacy';
    const CLI_DIR = './legacy/MintCLI/src';

    public function setupLegacyHtaccess($basePath)
    {
        $basePath = $basePath == '/' ? '/legacy' : $basePath . '/legacy';
        $originalHtaccess = file_get_contents(self::INSTANCE_DIR . '/.htaccess');
        $pattern = '/RewriteBase .*?\n/i';
        $replacement = "RewriteBase $basePath\n";
        $htaccess = preg_replace($pattern, $replacement, $originalHtaccess);
        file_put_contents(self::INSTANCE_DIR . '/.htaccess', $htaccess);
    }

    public function setupApplicationHtaccess($basePath)
    {
        $htaccessTemplate = file_get_contents(self::CLI_DIR . '/Assets/.htaccess');
        $htaccess = str_replace('__BASE_PATH__', $basePath, $htaccessTemplate);
        file_put_contents('./.htaccess', $htaccess);
    }
}
