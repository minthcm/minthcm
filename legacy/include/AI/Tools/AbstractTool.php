<?php

namespace MintHCM\AI\Tools;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

use MintHCM\AI\Tools\Config\ToolConfig;
use MintHCM\AI\Tools\Exceptions\ModuleNotAllowedException;

/**
 * Shared base for AI tool implementations. Owns the bits that MCP previously
 * had on AbstractMCPTool: ACL enforcement, deployment-wide blacklist, record
 * URL helpers.
 *
 * Concrete tools extend this class AND implement ToolInterface (declare
 * getName(), getSchema(), execute()).
 */
abstract class AbstractTool
{
    private ?ToolConfig $config = null;

    /**
     * Lazy accessor — Sugar's Administration store isn't available until the
     * legacy bootstrap has run, but we still want tool classes to be
     * constructable (e.g. during registry assembly).
     */
    protected function config(): ToolConfig
    {
        if ($this->config === null) {
            $this->config = ToolConfig::getInstance();
        }
        return $this->config;
    }

    /**
     * Enforces ACL + admin-configured blacklist for a module access.
     *
     * Reuses the existing `mcp_settings_blacklist` deployment setting so the
     * same restriction list governs both the agent loop and the MCP server.
     */
    protected function checkPermissions(string $module, string $acl_action = 'list'): bool
    {
        global $current_user;

        if (!$current_user || !$current_user->id) {
            throw new \RuntimeException('User not authenticated');
        }

        $blacklist = array_filter(array_map('trim', explode(',', (string) $this->config()->get('blacklist'))));

        if (in_array($module, $blacklist, true)) {
            throw new ModuleNotAllowedException("Access to module '{$module}' is not allowed by blacklist.");
        }

        if (!\ACLController::checkAccess($module, $acl_action, true, 'module', true)) {
            throw new ModuleNotAllowedException("Insufficient permissions for module: {$module}");
        }

        return true;
    }

    protected function getRecordUrl(string $module_name, string $id): string
    {
        global $sugar_config;
        if (!isset($sugar_config['site_url'])) {
            return '';
        }

        $base_url = rtrim((string) $sugar_config['site_url'], '/');

        return $base_url . '/#/modules/' . $module_name . '/DetailView/' . $id;
    }

    /**
     * Convenience for tools to return a successful ToolResult with an optional
     * structured payload appended as JSON for the model to consume.
     *
     * @param array<string, mixed> $payload
     */
    protected function successResult(string $message, array $payload = []): ToolResult
    {
        $text = $message;
        if ($payload !== []) {
            $encoded = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);
            if (is_string($encoded)) {
                $text .= "\n\n" . $encoded;
            }
        }
        return new ToolResult(true, $text, null, $payload !== [] ? $payload : null);
    }

    protected function errorResult(string $message): ToolResult
    {
        return ToolResult::fail($message);
    }

    protected function handleExecutionException(\Throwable $e): ToolResult
    {
        if ($e instanceof \InvalidArgumentException) {
            return $this->errorResult($e->getMessage());
        }

        $class_name = static::class;
        $short_class_name = str_contains($class_name, '\\')
            ? (string) substr($class_name, (int) strrpos($class_name, '\\') + 1)
            : $class_name;

        return $this->errorResult($short_class_name . ': ' . $e->getMessage());
    }
}
