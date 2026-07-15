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
require_once 'include/AI/bootstrap.php';
chdir('../mcp/');
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/../api/vendor/autoload.php';

require_once __DIR__ . '/MCPApp.php';
(new \MintMCP\MCPApp())->run();