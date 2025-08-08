<?php

// A MCP example server over STDIO with extra logging

// Enable comprehensive error logging
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log'); // Logs errors to a file
error_reporting(E_ALL);

require 'vendor/autoload.php';

use Mcp\Server\Server;
use Mcp\Server\ServerRunner;
use Mcp\Types\Prompt;
use Mcp\Types\PromptArgument;
use Mcp\Types\PromptMessage;
use Mcp\Types\ListPromptsResult;
use Mcp\Types\TextContent;
use Mcp\Types\Role;
use Mcp\Types\GetPromptResult;
use Mcp\Types\GetPromptRequestParams;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

// Create a logger
$logger = new Logger('mcp-server');

// Delete previous log
@unlink(__DIR__ . '/server_log.txt');

// Create a handler that writes to server_log.txt
$handler = new StreamHandler(__DIR__ . '/server_log.txt', Logger::DEBUG);

// Optional: Create a custom formatter to make logs more readable
$dateFormat = "Y-m-d H:i:s";
$output = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
$formatter = new LineFormatter($output, $dateFormat);
$handler->setFormatter($formatter);

// Add the handler to the logger
$logger->pushHandler($handler);

// Create a server instance
$server = new Server('example-server', $logger);

// Register prompt handlers
$server->registerHandler('prompts/list', function($params) {
    $prompt = new Prompt(
        name: 'example-prompt',
        description: 'An example prompt template',
        arguments: [
            new PromptArgument(
                name: 'arg1',
                description: 'Example argument',
                required: true
            )
        ]
    );
    return new ListPromptsResult([$prompt]);
});

$server->registerHandler('prompts/get', function(GetPromptRequestParams $params) {

    $name = $params->name;
    $arguments = $params->arguments;

    if ($name !== 'example-prompt') {
        throw new \InvalidArgumentException("Unknown prompt: {$name}");
    }

    // Get argument value safely
    $argValue = $arguments ? $arguments->arg1 : 'none';

    $prompt = new Prompt(
        name: 'example-prompt',
        description: 'An example prompt template',
        arguments: [
            new PromptArgument(
                name: 'arg1',
                description: 'Example argument',
                required: true
            )
        ]
    );

    return new GetPromptResult(
        messages: [
            new PromptMessage(
                role: Role::USER,
                content: new TextContent(
                    text: "Example prompt text with argument: $argValue"
                )
            )
        ],
        description: 'Example prompt'
    );
});

// Create initialization options and run server
$initOptions = $server->createInitializationOptions();
$runner = new ServerRunner($server, $initOptions, $logger);

try {
    $runner->run();
} catch (\Throwable $e) {
    echo "An error occurred: " . $e->getMessage() . "\n";
    $logger->error("Server run failed", ['exception' => $e]);
}