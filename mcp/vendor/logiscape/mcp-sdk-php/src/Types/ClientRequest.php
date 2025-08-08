<?php

/**
 * Model Context Protocol SDK for PHP
 *
 * (c) 2024 Logiscape LLC <https://logiscape.com>
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
 *
 * Filename: Types/ClientRequest.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Union type for client requests:
 * type ClientRequest =
 *   | InitializeRequest
 *   | PingRequest
 *   | ListResourcesRequest
 *   | ReadResourceRequest
 *   | SubscribeRequest
 *   | UnsubscribeRequest
 *   | ListPromptsRequest
 *   | GetPromptRequest
 *   | ListToolsRequest
 *   | CallToolRequest
 *   | SetLevelRequest
 *   | CompleteRequest
 *
 * This acts as a root model for that union and provides a factory method
 * to construct the correct request variant based on the method name and params.
 */
class ClientRequest implements McpModel {
    use ExtraFieldsTrait;

    private Request $request;

    /**
     * Construct a ClientRequest by passing a fully-instantiated Request subclass.
     */
    public function __construct(Request $request) {
        if (!(
            $request instanceof InitializeRequest ||
            $request instanceof PingRequest ||
            $request instanceof ListResourcesRequest ||
            $request instanceof ReadResourceRequest ||
            $request instanceof SubscribeRequest ||
            $request instanceof UnsubscribeRequest ||
            $request instanceof ListPromptsRequest ||
            $request instanceof GetPromptRequest ||
            $request instanceof ListToolsRequest ||
            $request instanceof CallToolRequest ||
            $request instanceof SetLevelRequest ||
            $request instanceof CompleteRequest ||
            $request instanceof ListTemplatesRequest
        )) {
            throw new \InvalidArgumentException('Invalid client request type');
        }
        $this->request = $request;
    }

    /**
     * Factory method to create a ClientRequest from a method string and parameters array.
     *
     * @param string $method The RPC method name
     * @param array|null $params The request parameters from the JSON-RPC message
     * @return self
     */
    public static function fromMethodAndParams(string $method, ?array $params): self {
        $params = $params ?? [];

        return match ($method) {
            'initialize' => self::createInitializeRequest($params),
            'ping' => new self(new PingRequest()),
            'completion/complete' => self::createCompleteRequest($params),
            'logging/setLevel' => self::createSetLevelRequest($params),
            'prompts/get' => self::createGetPromptRequest($params),
            'prompts/list' => self::createListPromptsRequest($params),
            'resources/list' => self::createListResourcesRequest($params),
            'resources/read' => self::createReadResourceRequest($params),
            'resources/subscribe' => self::createSubscribeRequest($params),
            'resources/unsubscribe' => self::createUnsubscribeRequest($params),
            'resources/templates/list' => self::createListTemplatesRequest($params),
            'tools/call' => self::createCallToolRequest($params),
            'tools/list' => self::createListToolsRequest($params),
            default => throw new \InvalidArgumentException("Unknown client request method: $method")
        };
    }

    private static function createInitializeRequest(array $params): self {
        // Handle capabilities
        $capParams = $params['capabilities'] ?? [];

        // ExperimentalCapabilities
        $experimental = null;
        if (isset($capParams['experimental'])) {
            $experimental = new ExperimentalCapabilities();
            foreach ($capParams['experimental'] as $k => $v) {
                $experimental->$k = $v;
            }
        }

        // ClientRootsCapability
        $roots = null;
        if (isset($capParams['roots'])) {
            $rootsData = $capParams['roots'];
            $listChanged = $rootsData['listChanged'] ?? null;
            unset($rootsData['listChanged']);
            $roots = new ClientRootsCapability($listChanged);
            foreach ($rootsData as $k => $v) {
                $roots->$k = $v;
            }
        }

        // SamplingCapability
        $sampling = null;
        if (isset($capParams['sampling'])) {
            $samplingData = $capParams['sampling'];
            $sampling = new SamplingCapability();
            foreach ($samplingData as $k => $v) {
                $sampling->$k = $v;
            }
        }

        $capabilities = new ClientCapabilities(
            roots: $roots,
            sampling: $sampling,
            experimental: $experimental
        );

        // Implementation
        if (!isset($params['clientInfo']['name'], $params['clientInfo']['version'])) {
            throw new \InvalidArgumentException('clientInfo must have name and version.');
        }
        $clientInfo = new Implementation(
            name: $params['clientInfo']['name'],
            version: $params['clientInfo']['version']
        );

        if (empty($params['protocolVersion'])) {
            throw new \InvalidArgumentException('protocolVersion is required for initialize.');
        }

        $initializeParams = new InitializeRequestParams(
            protocolVersion: $params['protocolVersion'],
            capabilities: $capabilities,
            clientInfo: $clientInfo,
            _meta: $params['_meta'] ?? null
        );

        return new self(new InitializeRequest($initializeParams));
    }

    private static function createCompleteRequest(array $params): self {
        $argumentData = $params['argument'] ?? [];
        if (empty($argumentData['name']) || !isset($argumentData['value'])) {
            throw new \InvalidArgumentException('CompleteRequest argument must have "name" and "value"');
        }

        $argument = new CompletionArgument($argumentData['name'], $argumentData['value']);

        $refData = $params['ref'] ?? [];
        if (!isset($refData['type'])) {
            throw new \InvalidArgumentException('CompleteRequest ref must have a "type"');
        }

        $ref = match ($refData['type']) {
            'ref/prompt' => new PromptReference($refData['name'] ?? ''),
            'ref/resource' => new ResourceReference($refData['uri'] ?? ''),
            default => throw new \InvalidArgumentException("Unknown ref type: {$refData['type']}")
        };

        // Construct the new CompleteRequestParams
        $reqParams = new CompleteRequestParams($argument, $ref);

        // Now pass that to CompleteRequest
        return new self(new CompleteRequest($reqParams));
    }

    private static function createSetLevelRequest(array $params): self {
        if (!isset($params['level'])) {
            throw new \InvalidArgumentException('SetLevelRequest "params" must include "level"');
        }
        $level = LoggingLevel::from($params['level']);
        return new self(new SetLevelRequest($level));
    }

    private static function createGetPromptRequest(array $params): self {
        if (empty($params['name'])) {
            throw new \InvalidArgumentException('GetPromptRequest requires "name"');
        }

        $arguments = null;
        if (isset($params['arguments'])) {
            $arguments = new PromptArguments($params['arguments']);
        }

        $getParams = new GetPromptRequestParams(
            name: $params['name'],
            arguments: $arguments
        );

        return new self(new GetPromptRequest($getParams));
    }

    private static function createListPromptsRequest(array $params): self {
        $cursor = $params['cursor'] ?? null;
        return new self(new ListPromptsRequest($cursor));
    }

    private static function createListResourcesRequest(array $params): self {
        $cursor = $params['cursor'] ?? null;
        return new self(new ListResourcesRequest($cursor));
    }

    private static function createReadResourceRequest(array $params): self {
        if (empty($params['uri'])) {
            throw new \InvalidArgumentException('ReadResourceRequest requires "uri"');
        }
        return new self(new ReadResourceRequest(uri: $params['uri']));
    }

    private static function createSubscribeRequest(array $params): self {
        if (empty($params['uri'])) {
            throw new \InvalidArgumentException('SubscribeRequest requires "uri"');
        }
        return new self(new SubscribeRequest(uri: $params['uri']));
    }

    private static function createUnsubscribeRequest(array $params): self {
        if (empty($params['uri'])) {
            throw new \InvalidArgumentException('UnsubscribeRequest requires "uri"');
        }
        return new self(new UnsubscribeRequest(uri: $params['uri']));
    }

    private static function createCallToolRequest(array $params): self {
        if (empty($params['name'])) {
            throw new \InvalidArgumentException('CallToolRequest requires "name"');
        }

        $arguments = $params['arguments'] ?? null;
        if ($arguments !== null && !is_array($arguments)) {
            throw new \InvalidArgumentException('"arguments" must be an associative array if provided.');
        }

        return new self(new CallToolRequest($params['name'], $arguments));
    }

    private static function createListTemplatesRequest(array $params): self {
        $cursor = $params['cursor'] ?? null;
        return new self(new ListTemplatesRequest($cursor));
    }

    private static function createListToolsRequest(array $params): self {
        $cursor = $params['cursor'] ?? null;
        return new self(new ListToolsRequest($cursor));
    }

    public function validate(): void {
        $this->request->validate();
    }

    public function getRequest(): Request {
        return $this->request;
    }

    public function jsonSerialize(): mixed {
        $data = $this->request->jsonSerialize();
        return array_merge((array)$data, $this->extraFields);
    }
}