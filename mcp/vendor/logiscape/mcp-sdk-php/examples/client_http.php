<?php

/**
 * Example MCP HTTP Client
 * 
 * This example demonstrates how to connect to an MCP server over HTTP
 * and list all available prompts, tools, and resources.
 * 
 * Usage: php mcp_http_client.php https://example.com/mcp [--insecure] [--headers=key:value,...]
 */

require_once __DIR__ . '/vendor/autoload.php';

use Mcp\Client\Client;
use Mcp\Types\Resource;
use Mcp\Types\Prompt;
use Mcp\Types\Tool;
use Psr\Log\LoggerInterface;

/**
 * A simple example client that connects to an MCP server over HTTP
 * and lists all available resources, prompts, and tools.
 */
class McpHttpClient {
    private Client $client;
    private ?string $endpoint = null;
    private array $headers = [];
    private array $options = [];
    private ?LoggerInterface $logger = null;
    
    public function __construct(LoggerInterface $logger = null) {
        $this->client = new Client($logger);
        $this->logger = $logger;
    }
    
    /**
     * Parse command line arguments.
     */
    public function parseArgs(array $argv): bool {
        // First argument should be the script name
        if (count($argv) < 2) {
            $this->showUsage();
            return false;
        }
        
        // Second argument should be the endpoint URL
        $this->endpoint = $argv[1];
        
        // Process any additional options
        for ($i = 2; $i < count($argv); $i++) {
            $arg = $argv[$i];
            
            if ($arg === '--insecure') {
                $this->options['verifyTls'] = false;
            } elseif (strpos($arg, '--headers=') === 0) {
                $headerStr = substr($arg, 10);
                $headerPairs = explode(',', $headerStr);
                
                foreach ($headerPairs as $pair) {
                    list($key, $value) = explode(':', $pair, 2);
                    $this->headers[trim($key)] = trim($value);
                }
            } elseif (strpos($arg, '--timeout=') === 0) {
                $this->options['readTimeout'] = (float)substr($arg, 10);
            } elseif (strpos($arg, '--no-sse') === 0) {
                $this->options['enableSse'] = false;
            } elseif (strpos($arg, '--help') === 0 || $arg === '-h') {
                $this->showUsage();
                return false;
            } else {
                echo "Unknown option: {$arg}\n";
                $this->showUsage();
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Display usage information.
     */
    private function showUsage(): void {
        echo "Usage: php mcp_http_client.php <endpoint> [options]\n";
        echo "\n";
        echo "Options:\n";
        echo "  --insecure            Disable TLS certificate verification\n";
        echo "  --headers=key:value   Add HTTP headers (comma-separated key:value pairs)\n";
        echo "  --timeout=<seconds>   Set read timeout in seconds (default: 60)\n";
        echo "  --no-sse              Disable Server-Sent Events\n";
        echo "  --help, -h            Show this help message\n";
        echo "\n";
        echo "Example:\n";
        echo "  php mcp_http_client.php https://example.com/mcp --headers=Authorization:Bearer\ token\n";
    }
    
    /**
     * Run the client.
     */
    public function run(): int {
        if ($this->endpoint === null) {
            return 1;
        }
        
        try {
            echo "Connecting to MCP server at {$this->endpoint}...\n";
            
            // Create a session
            $session = $this->client->connect(
                commandOrUrl: $this->endpoint,
                args: $this->headers,
                env: $this->options
            );
            
            echo "Connection established successfully.\n\n";
            
            // Get server information from the initialize result
            $initResult = $session->getInitializeResult();
            
            echo "Server Information:\n";
            echo "  Name: {$initResult->serverInfo->name}\n";
            echo "  Version: {$initResult->serverInfo->version}\n";
            echo "  Protocol Version: {$initResult->protocolVersion}\n";
            
            if (isset($initResult->instructions)) {
                echo "  Instructions: " . substr($initResult->instructions, 0, 100) . 
                     (strlen($initResult->instructions) > 100 ? "..." : "") . "\n";
            }
            
            echo "\nServer Capabilities:\n";
            $this->printCapabilities($initResult->capabilities);
            
            // List resources if supported
            if (isset($initResult->capabilities->resources)) {
                echo "\nResources:\n";
                $this->listResources($session);
            } else {
                echo "\nServer does not support resources.\n";
            }
            
            // List prompts if supported
            if (isset($initResult->capabilities->prompts)) {
                echo "\nPrompts:\n";
                $this->listPrompts($session);
            } else {
                echo "\nServer does not support prompts.\n";
            }
            
            // List tools if supported
            if (isset($initResult->capabilities->tools)) {
                echo "\nTools:\n";
                $this->listTools($session);
            } else {
                echo "\nServer does not support tools.\n";
            }
            
            // Clean up
            $this->client->close();
            echo "\nConnection closed.\n";
            
            return 0;
        } catch (\Exception $e) {
            echo "Error: {$e->getMessage()}\n";
            return 1;
        }
    }
    
    /**
     * Print server capabilities.
     */
    private function printCapabilities(object $capabilities): void {
        $props = get_object_vars($capabilities);
        foreach ($props as $key => $value) {
            if ($value === null) {
                continue;
            }
            
            echo "  {$key}: ";
            if (is_object($value)) {
                echo "\n";
                $subProps = get_object_vars($value);
                foreach ($subProps as $subKey => $subValue) {
                    echo "    {$subKey}: " . $this->formatValue($subValue) . "\n";
                }
            } else {
                echo $this->formatValue($value) . "\n";
            }
        }
    }
    
    /**
     * Format a value for display.
     */
    private function formatValue(mixed $value): string {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        } elseif (is_array($value)) {
            return json_encode($value);
        } elseif (is_object($value)) {
            return json_encode($value);
        } else {
            return (string)$value;
        }
    }
    
    /**
     * List resources from the server.
     */
    private function listResources($session): void {
        try {
            $result = $session->listResources();
            
            if (empty($result->resources)) {
                echo "  No resources available.\n";
                return;
            }
            
            foreach ($result->resources as $resource) {
                echo "  • {$resource->name}\n";
                echo "    URI: {$resource->uri}\n";
                
                if (isset($resource->description)) {
                    echo "    Description: {$resource->description}\n";
                }
                
                if (isset($resource->mimeType)) {
                    echo "    MIME Type: {$resource->mimeType}\n";
                }
                
                echo "\n";
            }
            
            // Check for pagination
            if (isset($result->nextCursor)) {
                echo "  More resources available (pagination cursor: {$result->nextCursor}).\n";
            }
        } catch (\Exception $e) {
            echo "  Error listing resources: {$e->getMessage()}\n";
        }
    }
    
    /**
     * List prompts from the server.
     */
    private function listPrompts($session): void {
        try {
            $result = $session->listPrompts();
            
            if (empty($result->prompts)) {
                echo "  No prompts available.\n";
                return;
            }
            
            foreach ($result->prompts as $prompt) {
                echo "  • {$prompt->name}\n";
                
                if (isset($prompt->description)) {
                    echo "    Description: {$prompt->description}\n";
                }
                
                if (!empty($prompt->arguments)) {
                    echo "    Arguments:\n";
                    foreach ($prompt->arguments as $arg) {
                        echo "      - {$arg->name}";
                        echo isset($arg->required) && $arg->required ? " (required)" : " (optional)";
                        
                        if (isset($arg->description)) {
                            echo ": {$arg->description}";
                        }
                        
                        echo "\n";
                    }
                }
                
                echo "\n";
            }
            
            // Check for pagination
            if (isset($result->nextCursor)) {
                echo "  More prompts available (pagination cursor: {$result->nextCursor}).\n";
            }
        } catch (\Exception $e) {
            echo "  Error listing prompts: {$e->getMessage()}\n";
        }
    }
    
    /**
     * List tools from the server.
     */
    private function listTools($session): void {
        try {
            $result = $session->listTools();
            
            if (empty($result->tools)) {
                echo "  No tools available.\n";
                return;
            }
            
            foreach ($result->tools as $tool) {
                echo "  • {$tool->name}\n";
                
                if (isset($tool->description)) {
                    echo "    Description: {$tool->description}\n";
                }
                
                if (isset($tool->inputSchema) && isset($tool->inputSchema->properties)) {
                    echo "    Parameters:\n";
                    
                    $required = $tool->inputSchema->required ?? [];
                    
                    foreach ($tool->inputSchema->properties as $paramName => $paramProps) {
                        echo "      - {$paramName}";
                        echo in_array($paramName, $required) ? " (required)" : " (optional)";
                        
                        if (isset($paramProps->type)) {
                            echo " [{$paramProps->type}]";
                        }
                        
                        if (isset($paramProps->description)) {
                            echo ": {$paramProps->description}";
                        }
                        
                        echo "\n";
                    }
                }
                
                if (isset($tool->annotations)) {
                    echo "    Annotations:\n";
                    foreach (get_object_vars($tool->annotations) as $key => $value) {
                        echo "      {$key}: " . $this->formatValue($value) . "\n";
                    }
                }
                
                echo "\n";
            }
            
            // Check for pagination
            if (isset($result->nextCursor)) {
                echo "  More tools available (pagination cursor: {$result->nextCursor}).\n";
            }
        } catch (\Exception $e) {
            echo "  Error listing tools: {$e->getMessage()}\n";
        }
    }
}

// Run the client when executed directly
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'])) {
    $client = new McpHttpClient();
    
    if ($client->parseArgs($argv)) {
        exit($client->run());
    } else {
        exit(1);
    }
}