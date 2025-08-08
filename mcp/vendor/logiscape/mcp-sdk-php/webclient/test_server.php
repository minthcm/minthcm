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
 * A MCP server with example prompts, tools, and resources for testing
 */

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
use Mcp\Types\Tool;
use Mcp\Types\ToolInputSchema;
use Mcp\Types\ToolInputProperties;
use Mcp\Types\ListToolsResult;
use Mcp\Types\CallToolResult;
use Mcp\Types\Resource;
use Mcp\Types\ResourceTemplate;
use Mcp\Types\ListResourcesResult;
use Mcp\Types\ListResourceTemplatesResult;
use Mcp\Types\ReadResourceResult;
use Mcp\Types\TextResourceContents;

// Create a server instance
$server = new Server('mcp-test-server');

// Register prompt handlers (keeping existing code)
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

// Add tool handlers
$server->registerHandler('tools/list', function($params) {
    // Create properties object first
    $properties = ToolInputProperties::fromArray([
        'num1' => [
            'type' => 'number',
            'description' => 'First number'
        ],
        'num2' => [
            'type' => 'number',
            'description' => 'Second number'
        ]
    ]);

    // Create schema with properties and required fields
    $inputSchema = new ToolInputSchema(
        properties: $properties,
        required: ['num1', 'num2']
    );

    $tool = new Tool(
        name: 'add-numbers',
        description: 'Adds two numbers together',
        inputSchema: $inputSchema
    );

    return new ListToolsResult([$tool]);
});

$server->registerHandler('tools/call', function($params) {
    $name = $params->name;
    $arguments = $params->arguments ?? [];

    if ($name !== 'add-numbers') {
        throw new \InvalidArgumentException("Unknown tool: {$name}");
    }

    // Validate and convert arguments to numbers
    $num1 = filter_var($arguments['num1'] ?? null, FILTER_VALIDATE_FLOAT);
    $num2 = filter_var($arguments['num2'] ?? null, FILTER_VALIDATE_FLOAT);

    if ($num1 === false || $num2 === false) {
        return new CallToolResult(
            content: [new TextContent(
                text: "Error: Both arguments must be valid numbers"
            )],
            isError: true
        );
    }

    $sum = $num1 + $num2;
    return new CallToolResult(
        content: [new TextContent(
            text: "The sum of {$num1} and {$num2} is {$sum}"
        )]
    );
});

// Add resource handlers
$server->registerHandler('resources/list', function($params) {
    $resource = new Resource(
        uri: 'example://greeting',
        name: 'Greeting Text',
        description: 'A simple greeting message',
        mimeType: 'text/plain'
    );
    return new ListResourcesResult([$resource]);
});

$server->registerHandler('resources/read', function($params) {
    $uri = $params->uri;
    if ($uri !== 'example://greeting') {
        throw new \InvalidArgumentException("Unknown resource: {$uri}");
    }

    return new ReadResourceResult(
        contents: [new TextResourceContents(
            uri: $uri,
            text: "Hello from the example MCP server!",
            mimeType: 'text/plain'
        )]
    );
});

// Add resource template handlers
$server->registerHandler('resources/templates/list', function($params) {
    // Create properties object first
    $properties = ToolInputProperties::fromArray([
        'name' => [
            'type' => 'string',
            'description' => 'Name of the person to greet'
        ]
    ]);

    // Create schema with properties and required fields
    $inputSchema = new ToolInputSchema(
        properties: $properties,
        required: ['name']
    );

    // Create template instance with variable placeholder and schema
    $template = new ResourceTemplate(
        name: 'get_greeting',
        uriTemplate: 'greeting://{name}',
        description: 'Get a personalized greeting',
        mimeType: 'text/plain'
    );
    $template->inputSchema = $inputSchema;

    return new ListResourceTemplatesResult([$template]);
});

// Create initialization options and run server
$notificationOptions = new \Mcp\Server\NotificationOptions(
    resourcesChanged: true
);
$initOptions = $server->createInitializationOptions($notificationOptions);
$runner = new ServerRunner($server, $initOptions);

$runner->run();