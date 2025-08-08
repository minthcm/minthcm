# Model Context Protocol SDK for PHP

English | [中文](README.zh-CN.md)

This package provides a PHP implementation of the [Model Context Protocol](https://modelcontextprotocol.io), allowing applications to provide context for LLMs in a standardized way. It separates the concerns of providing context from the actual LLM interaction.

## Overview

This PHP SDK implements the full MCP specification, making it easy to:
* Build MCP clients that can connect to any MCP server
* Create MCP servers that expose resources, prompts and tools
* Use standard transports like stdio and HTTP
* Handle all MCP protocol messages and lifecycle events

This SDK began as a PHP port of the official [Python SDK](https://github.com/modelcontextprotocol/python-sdk) for the Model Context Protocol. It has since been expanded to fully support MCP using native PHP functions, helping to maximize compatibility with most standard web hosting environments.

This SDK is primarily targeted at developers working on frontier AI integration solutions. Some functionality may be incomplete and implementations should undergo thorough testing and security review by experienced developers prior to production use.

## Installation

You can install the package via composer:

```bash
composer require logiscape/mcp-sdk-php
```

### Requirements
* PHP 8.1 or higher
* ext-curl
* ext-json
* ext-pcntl (optional, recommended for CLI environments)
* monolog/monolog (optional, used by example clients/servers for logging)

## Basic Usage

### Creating an MCP Server

Here's a complete example of creating an MCP server that provides prompts:

```php
<?php

// A basic example server with a list of prompts for testing

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

// Create a server instance
$server = new Server('example-server');

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
$runner = new ServerRunner($server, $initOptions);
$runner->run();
```

Save this as `example_server.php`

### Creating an MCP Client

Here's how to create a client that connects to the example server:

```php
<?php

// A basic example client that connects to example_server.php and outputs the prompts

require 'vendor/autoload.php';

use Mcp\Client\Client;
use Mcp\Client\Transport\StdioServerParameters;
use Mcp\Types\TextContent;

// Create server parameters for stdio connection
$serverParams = new StdioServerParameters(
    command: 'php',  // Executable
    args: ['example_server.php'],  // File path to the server
    env: null  // Optional environment variables
);

// Create client instance
$client = new Client();

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
```

Save this as `example_client.php` and run it:
```bash
php example_client.php
```

## Advanced Examples

The "examples" directory includes additional clients and servers for both the STDIO and HTTP transports. All examples are designed to run in the same directory where you installed the SDK.

Some examples use monolog for logging, which can be installed via composer:

```bash
composer require monolog/monolog
```

## MCP Web Client

The "webclient" directory includes a web-based application for testing MCP servers. It was designed to demonstrate a MCP client capable of running in a typical web hosting environment.

### Setting Up The Web Client

To setup the web client, upload the contents of "webclient" to a web directory, such as public_html on a cPanel server. Ensure that the MCP SDK for PHP is installed in that same directory by running the Composer command found in the Installation section of this README.

### Using The Web Client

Once the web client has been uploaded to a web directory, navigate to index.php to open the interface. To connect to the included MCP test server, enter `php` in the Command field and `test_server.php` in the Arguments field and click `Connect to Server`. The interface allows you to test Prompts, Tools, and Resources. There is also a Debug Panel allowing you to view the JSON-RPC messages being sent between the Client and Server.

### Web Client Notes And Limitations

This MCP Web Client is intended for developers to test MCP servers, and it is not recommended to be made publicly accessible as a web interface without additional testing for security, error handling, and resource management.

While MCP is usually implemented as a stateful session protocol, a typical PHP-based web hosting environment restricts long-running processes. To maximize compatibility, the MCP Web Client will initialize a new connection between the client and server for every request, and then close that connection after the request is complete.

## OAuth Authorization (Currently In Testing)

The HTTP server transport includes optional OAuth 2.1 support. For more details see the [OAuth Authentication Example](examples/server_auth/README.md).

## Documentation

For detailed information about the Model Context Protocol, visit the [official documentation](https://modelcontextprotocol.io).

## 2025-03-26 Implementation

We are currently implementing the 2025-03-26 revision of the MCP Spec.

### Completed Tasks
- Implement protocol version negotiation
- Create classes for new spec features
- Add support for JSON-RPC batching
- Implement HTTP transport

### Available For Testing
- Implement server side authorization framework based on OAuth 2.1

### To Do
- Implement client side authorization framework based on OAuth 2.1
- Explore the feasibility of supporting SSE in PHP environments

## Credits

This PHP SDK was developed by:
- [Josh Abbott](https://joshabbott.com)
- Claude 3.5 Sonnet
- Claude Opus 4

Additional debugging and refactoring done by Josh Abbott using OpenAI ChatGPT o1 pro mode and OpenAI Codex.

Based on the original [Python SDK](https://github.com/modelcontextprotocol/python-sdk) for the Model Context Protocol.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
