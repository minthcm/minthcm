<?php

namespace MintHCM\AI\Tools\Config;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * Shared settings backend for AI / MCP tools.
 *
 * Historically the same keys lived under SugarCRM's Administration store with
 * a `mcp_settings` prefix (e.g. `blacklist`, `max_pagination_limit`). We keep
 * that prefix so existing settings remain compatible and a single source of
 * truth governs both the agent loop and the MCP server.
 */
class ToolConfig
{
    private const SETTINGS_KEY = 'mcp_settings';
    private static ?ToolConfig $instance = null;
    private array $config = [];

    private function __construct()
    {
        $admin = new \Administration();
        $admin->retrieveSettings(self::SETTINGS_KEY);
        $this->config = $admin->settings;
    }

    public static function getInstance(): ToolConfig
    {
        if (self::$instance === null) {
            self::$instance = new ToolConfig();
        }
        return self::$instance;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $full_key = self::SETTINGS_KEY . '_' . $key;
        return $this->config[$full_key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $admin = new \Administration();
        $admin->saveSetting(self::SETTINGS_KEY, $key, $value);
        $this->config[self::SETTINGS_KEY . '_' . $key] = $value;
    }
}
