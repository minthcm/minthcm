<?php

namespace MintMCP;

use JsonException;
use Mcp\Capability\Discovery\DocBlockParser;
use Mcp\Capability\Discovery\SchemaGenerator;
use Mcp\Server;
use MintMCP\Auth\AuthManager;
use MintMCP\Auth\OAuth2Server;
use MintMCP\Capabilities\Tools\BrowseDocumentationTool;
use MintMCP\Server\DatabaseSessionStore;
use ReflectionMethod;

final class MCPApp
{
    public function run(): void
    {
        require_once __DIR__.'/bootstrap.php';
        chdir(__DIR__);
        $this->handleCors();
        $this->handleNonMcpRequests();

        logger()->info('Starting MintHCM MCP Server...');
        $headers = getallheaders();
        $authManager = $this->authenticate($headers);
        if (!$authManager->validate()) {
            $oauth2Server = OAuth2Server::getInstance();
            $oauth2Server->sendOAuthChallenge();
            exit(1);
        }

        $schemaGenerator = new SchemaGenerator(new DocBlockParser(logger: logger()));
        $browseDocMethod = new ReflectionMethod(BrowseDocumentationTool::class, 'browseDocumentation');
        $browseDocRegistration = BrowseDocumentationTool::buildManualRegistrationData($schemaGenerator, $browseDocMethod);

        # Fixme: Migrate from custom oauth2 server to use oauth Middleware when mcp-sdk will be updated to support it (https://github.com/modelcontextprotocol/php-sdk/milestone/5)
        $server = Server::builder()
            ->setServerInfo('MintHCM MCP Server', '1.0.0', 'MintHCM MCP Server')
            ->addTool(
                [BrowseDocumentationTool::class, 'browseDocumentation'],
                'browse_documentation',
                $browseDocRegistration['description'],
                null,
                $browseDocRegistration['inputSchema'],
            )
            ->setInstructions(
                "This MCP server provides tools to search, create, update, and delete records across MintHCM modules.\n\n" .
                "## Documentation-first workflow\n" .
                "Before performing any task, check the built-in documentation:\n" .
                "1. Call browse_documentation with a category relevant to your task.\n" .
                "2. Call get_documentation_entry for each entry you need.\n" .
                "3. Follow the processes and patterns described in the documentation.\n\n" .
                "You may browse multiple categories. If a category has no entries, try a different one."
            )
            ->setContainer(container())
            ->setSession(new DatabaseSessionStore())
            ->setLogger(logger())
            ->setDiscovery(__DIR__, ['Capabilities'], ['vendor', 'sessions'])
            ->build();

        try {
            $result = $server->run(transport());
        } catch (JsonException $e) {
            logger()->warning('Invalid JSON received by MCP transport.', ['message' => $e->getMessage()]);
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode([
                'jsonrpc' => '2.0',
                'id' => null,
                'error' => ['code' => -32700, 'message' => 'Parse error'],
            ]);
            exit(0);
        }

        logger()->info('Server listener stopped gracefully.', ['result' => $result]);

        shutdown($result);
    }

    private function handleCors(): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Authorization, Content-Type, Accept');

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
            http_response_code(200);
            exit(0);
        }
    }

    private function handleNonMcpRequests(): void
    {
        if ('cli' === \PHP_SAPI) {
            return;
        }

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if ($method === 'GET') {
            $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
            if (str_contains($accept, 'text/event-stream')) {
                return;
            }
            header('Content-Type: application/json');
            echo json_encode(['status' => 'online', 'version' => '1.1.0']);
            exit(0);
        }

        if ($method !== 'POST') {
            http_response_code(405);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'method not allowed']);
            exit(0);
        }
    }

    private function authenticate(array $headers): AuthManager
    {
        $auth = $headers['Authorization'] ?? $headers['authorization'] ?? null;
        if (!$auth || !preg_match('/Bearer\s+(\S+)/', $auth, $matches)) {
            $oauth2Server = OAuth2Server::getInstance();
            $oauth2Server->sendOAuthChallenge();
            exit(1);
        }

        $token = $matches[1];
        return AuthManager::getInstance($token);
    }

}