<?php

/**
 * Model Context Protocol SDK for PHP
 *
 * (c) 2025 Logiscape LLC <https://logiscape.com>
 *
 * Based on the Python SDK for the Model Context Protocol
 * https://github.com/modelcontextprotocol/python-sdk
 *
 * PHP conversion developed by:
 * - Josh Abbott
 * - Claude 3.5 Sonnet (Anthropic AI model)
 * - ChatGPT o1 pro mode
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
 
 /**
 * Main interface for MCP Web Client
 */

// Ensure the SDK is installed
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
	echo("Error: The Model Context Protocol SDK for PHP must be installed in the current directory. See https://github.com/logiscape/mcp-sdk-php for details.");
	exit;
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MCP Server Tester</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles -->
    <style>
        .debug-panel {
            background-color: #1e1e1e;
            color: #d4d4d4;
            font-family: 'Consolas', 'Monaco', monospace;
            padding: 15px;
            border-radius: 5px;
            max-height: 300px;
            overflow-y: auto;
        }
        
        .debug-panel .timestamp {
            color: #569cd6;
        }
        
        .debug-panel .level {
            color: #4ec9b0;
        }
        
        .debug-panel .message {
            color: #ce9178;
        }
        
        .server-panel {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .capability-panel {
            display: none;
            margin-top: 20px;
        }
        
        .loading-spinner {
            display: none;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">MCP Server Tester</a>
        </div>
    </nav>
    
    <div id="notification-area" class="position-fixed top-0 end-0 p-3" style="z-index: 1100;">
        <!-- Notifications will be inserted here -->
    </div>

    <div class="container mt-4">
        <!-- Connection Panel -->
        <div class="server-panel">
            <h4>Server Connection</h4>
            <form id="connection-form" class="row g-3">
                <div class="col-md-4">
                    <label for="command" class="form-label">Command</label>
                    <input type="text" class="form-control" id="command" required>
                </div>
                <div class="col-md-4">
                    <label for="args" class="form-label">Arguments (one per line)</label>
                    <textarea class="form-control" id="args" rows="3"></textarea>
                </div>
                <div class="col-md-4">
                    <label for="env" class="form-label">Environment Variables (KEY=VALUE, one per line)</label>
                    <textarea class="form-control" id="env" rows="3"></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary" id="connect-btn">
                        <span class="spinner-border spinner-border-sm loading-spinner" role="status" aria-hidden="true"></span>
                        Connect to Server
                    </button>
                    <button type="button" class="btn btn-danger" id="disconnect-btn" disabled>Disconnect</button>
                </div>
            </form>
        </div>

        <!-- Capability Panels -->
        <div id="capabilities-container">
            <!-- Prompts Panel -->
            <div class="capability-panel" id="prompts-panel">
                <h4>Prompts</h4>
                <div class="mb-3">
                    <button class="btn btn-primary" id="list-prompts-btn">List Prompts</button>
                </div>
                <div id="prompt-details" class="d-none">
                    <h5>Execute Prompt</h5>
                    <form id="prompt-form" class="mb-3">
                        <div class="mb-3">
                            <select class="form-select" id="prompt-select">
                                <option value="">Select a prompt...</option>
                            </select>
                        </div>
                        <div id="prompt-arguments">
                            <!-- Arguments will be dynamically added here -->
                        </div>
                        <button type="submit" class="btn btn-primary" disabled>Execute Prompt</button>
                    </form>
                </div>
                <div class="mt-3" id="prompts-result"></div>
            </div>

            <!-- Tools Panel -->
            <div class="capability-panel" id="tools-panel">
                <h4>Tools</h4>
                <div class="mb-3">
                    <button class="btn btn-primary" id="list-tools-btn">List Tools</button>
                </div>
                <div id="tool-details" class="d-none">
                    <h5>Execute Tool</h5>
                    <form id="tool-form" class="mb-3">
                        <div class="mb-3">
                            <select class="form-select" id="tool-select">
                                <option value="">Select a tool...</option>
                            </select>
                        </div>
                        <div id="tool-arguments">
                            <!-- Arguments will be dynamically added here -->
                        </div>
                        <button type="submit" class="btn btn-primary" disabled>Execute Tool</button>
                    </form>
                </div>
                <div class="mt-3" id="tools-result"></div>
            </div>

            <!-- Resources Panel -->
            <div class="capability-panel" id="resources-panel">
                <h4>Resources</h4>
                <div class="mb-3">
                    <button class="btn btn-primary" id="list-resources-btn">List Resources</button>
                </div>
                <div id="resource-details" class="d-none">
                    <h5>View Resource</h5>
                    <form id="resource-form" class="mb-3">
                        <div class="mb-3">
                            <select class="form-select" id="resource-select">
                                <option value="">Select a resource...</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" disabled>View Resource</button>
                    </form>
                </div>
                <div class="mt-3" id="resources-result"></div>
            </div>
        </div>

        <!-- Debug Panel -->
        <div class="mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Debug Log</h4>
                <button class="btn btn-secondary btn-sm" id="toggle-debug">Toggle Debug Panel</button>
                <button class="btn btn-danger btn-sm me-2" id="clear-debug">Clear Logs</button>
            </div>
            <div class="debug-panel mt-2" id="debug-panel" style="display:none;">
                <!-- Log entries will be inserted here -->
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="js/mcp-client.js"></script>

</body>
</html>