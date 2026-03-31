<?php

namespace MintMCP\Config;

class Config
{
    private const SETTINGS_KEY = 'mcp_settings';
    private static ?Config $instance = null;
    private array $config = [];

    private function __construct()
    {
        $admin = new \Administration();
        $admin->retrieveSettings(self::SETTINGS_KEY);
        $this->config = $admin->settings;
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
        $fullKey = self::SETTINGS_KEY . '_' . $key;
        return $this->config[$fullKey] ?: $default;
    }

    public function set(string $key, $value): void
    {
        $admin = new \Administration();
        $admin->saveSetting(self::SETTINGS_KEY, $key, $value);
        $this->config[self::SETTINGS_KEY . '_' . $key] = $value;
    }
}
