<?php

/**
 * MintHCM MCP entry-point
 * ──────────────────────
 */

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}
chdir('../legacy/');
require_once 'include/entryPoint.php';
chdir('../mcp/');
require_once __DIR__ . '/vendor/autoload.php';

(new \MintMCP\MCPApp())->run();
