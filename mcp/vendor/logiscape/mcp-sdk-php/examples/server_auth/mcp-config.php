<?php

/**
 * MCP Authentication Configuration
 * 
 * (c) 2025 Logiscape LLC <https://logiscape.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    logiscape/mcp-sdk-php
 * @author     Josh Abbott <https://joshabbott.com>
 * @copyright  Logiscape LLC
 * @license    MIT License
 * @link       https://github.com/logiscape/mcp-sdk-php
 */

 // Set configuration constants
define('MCP_JWT_SECRET', 'test-secret-key-change-in-production');
define('MCP_AUTH_ISSUER', 'https://example.com/auth_server');
define('MCP_RESOURCE_ID', 'https://yoursite.com/server_auth.php');