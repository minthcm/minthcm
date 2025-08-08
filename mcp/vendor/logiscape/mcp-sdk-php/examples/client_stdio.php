<?php

// A MCP example client over STDIO with extra logging

// Enable comprehensive error reporting and logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log'); // Logs errors to a file
error_reporting(E_ALL);

require 'vendor/autoload.php';

use Mcp\Client\Client;
use Mcp\Client\Transport\StdioServerParameters;
use Mcp\Types\TextContent;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

// Create a logger
$logger = new Logger('mcp-client');

// Delete previous log
@unlink(__DIR__ . '/client_log.txt');

// Create a handler that writes to client_log.txt
$handler = new StreamHandler(__DIR__ . '/client_log.txt', Logger::DEBUG);

// Optional: Create a custom formatter to make logs more readable
$dateFormat = "Y-m-d H:i:s";
$output = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
$formatter = new LineFormatter($output, $dateFormat);
$handler->setFormatter($formatter);

// Add the handler to the logger
$logger->pushHandler($handler);

// Create server parameters for stdio connection
$serverParams = new StdioServerParameters(
    command: 'php',  // Executable
    args: ['server_stdio.php'],  // File path to the server
    env: null  // Optional environment variables
);

echo("Creating client\n");

// Create client instance
$client = new Client($logger);

try {
    echo("Starting to connect\n");
    // Connect to the server using stdio transport
    $session = $client->connect(
        commandOrUrl: $serverParams->getCommand(),
        args: $serverParams->getArgs(),
        env: $serverParams->getEnv()
    );

    echo("Starting to get available prompts\n");
    // List available prompts
    $promptsResult = $session->listPrompts();

    // Output the list of prompts
    if (!empty($promptsResult->prompts)) {
        echo "Available prompts:\n";
        foreach ($promptsResult->prompts as $prompt) {
            echo "  - Name: " . $prompt->name . "\n";
            echo "    Description: " . $prompt->description . "\n";
            echo "    Arguments:\n";
            if (!empty($prompt->arguments)) {
                foreach ($prompt->arguments as $argument) {
                    echo "      - " . $argument->name . " (" . ($argument->required ? "required" : "optional") . "): " . $argument->description . "\n";
                }
            } else {
                echo "      (None)\n";
            }
        }
    } else {
        echo "No prompts available.\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
} finally {
    // Close the server connection
    if (isset($client)) {
        $client->close();
    }
}