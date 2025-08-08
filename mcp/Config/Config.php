<?php

namespace MintMCP\Config;

class Config
{
    private static ?Config $instance = null;
    private array $config = [];

    private function __construct()
    {
        $configFile = __DIR__ . '/mcp_conifg.php';
        if (file_exists($configFile)) {
            // Isolate scope to avoid variable pollution
            $mcp_config = [];
            include $configFile;
            if (isset($mcp_config) && is_array($mcp_config)) {
                $this->config = $mcp_config;
            }
        }
    }

    public static function getInstance(): Config
    {
        if (self::$instance === null) {
            self::$instance = new Config();
        }
        return self::$instance;
    }

    public function get(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }
}