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
 * Filename: Types/ServerRequest.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Union type for server requests:
 * type ServerRequest =
 *   | PingRequest
 *   | CreateMessageRequest
 *   | ListRootsRequest
 */
class ServerRequest implements McpModel {
    use ExtraFieldsTrait;

    private Request $request;

    public function __construct(Request $request) {
        if (!(
            $request instanceof PingRequest ||
            $request instanceof CreateMessageRequest ||
            $request instanceof ListRootsRequest
        )) {
            throw new \InvalidArgumentException('Invalid server request type');
        }
        $this->request = $request;
    }

    /**
     * Factory method to create a ServerRequest from method and params.
     *
     * @param string $method
     * @param array|null $params
     * @return self
     */
    public static function fromMethodAndParams(string $method, ?array $params): self {
        $params = $params ?? [];

        return match ($method) {
            'ping' => new self(new PingRequest()),
            'sampling/createMessage' => self::createCreateMessageRequest($params),
            'roots/list' => new self(new ListRootsRequest()),
            default => throw new \InvalidArgumentException("Unknown server request method: $method")
        };
    }

    private static function createCreateMessageRequest(array $params): self {
        // According to schema, CreateMessageRequest params:
        // {
        //   messages: SamplingMessage[],
        //   maxTokens: number,
        //   stopSequences?: string[],
        //   systemPrompt?: string,
        //   temperature?: number,
        //   metadata?: object,
        //   modelPreferences?: ModelPreferences,
        //   includeContext?: "none" | "thisServer" | "allServers"
        // }

        if (!isset($params['messages']) || !is_array($params['messages'])) {
            throw new \InvalidArgumentException('CreateMessageRequest requires "messages" array');
        }
        if (!isset($params['maxTokens']) || !is_numeric($params['maxTokens'])) {
            throw new \InvalidArgumentException('CreateMessageRequest requires "maxTokens" as a number');
        }

        // Construct SamplingMessages
        $messages = [];
        foreach ($params['messages'] as $m) {
            $messages[] = self::createSamplingMessage($m);
        }

        $maxTokens = (int)$params['maxTokens'];
        $stopSequences = $params['stopSequences'] ?? null;
        if ($stopSequences !== null && !is_array($stopSequences)) {
            throw new \InvalidArgumentException('stopSequences must be an array of strings if provided');
        }

        $systemPrompt = $params['systemPrompt'] ?? null;
        $temperature = isset($params['temperature']) ? (float)$params['temperature'] : null;

        // Metadata and modelPreferences are more complex. They may be arbitrary structures:
        $metadata = null;
        if (isset($params['metadata'])) {
            // Metadata is a Meta object (assuming we have a Meta class that accepts arbitrary fields)
            $metadata = new Meta();
            foreach ($params['metadata'] as $k => $v) {
                $metadata->$k = $v;
            }
        }

        $modelPreferences = null;
        if (isset($params['modelPreferences'])) {
            $modelPreferences = self::createModelPreferences($params['modelPreferences']);
        }

        $includeContext = $params['includeContext'] ?? null;

        return new self(new CreateMessageRequest(
            messages: $messages,
            maxTokens: $maxTokens,
            stopSequences: $stopSequences,
            systemPrompt: $systemPrompt,
            temperature: $temperature,
            metadata: $metadata,
            modelPreferences: $modelPreferences,
            includeContext: $includeContext
        ));
    }

    private static function createSamplingMessage(array $m): SamplingMessage {
        // SamplingMessage: { role: "user"|"assistant", content: TextContent|ImageContent }
        if (!isset($m['role']) || !in_array($m['role'], ['user', 'assistant'], true)) {
            throw new \InvalidArgumentException('SamplingMessage requires a valid role');
        }
        if (!isset($m['content'])) {
            throw new \InvalidArgumentException('SamplingMessage requires a content field');
        }

        $content = self::createSamplingContent($m['content']);
        return new SamplingMessage(role: $m['role'], content: $content);
    }

    private static function createSamplingContent(array $c): TextContent|ImageContent {
        if (!isset($c['type'])) {
            throw new \InvalidArgumentException('SamplingMessage content requires a type');
        }

        return match ($c['type']) {
            'text' => self::createTextContent($c),
            'image' => self::createImageContent($c),
            default => throw new \InvalidArgumentException("Unknown content type: {$c['type']}")
        };
    }

    private static function createTextContent(array $c): TextContent {
        if (!isset($c['text'])) {
            throw new \InvalidArgumentException('TextContent requires text field');
        }
        $text = $c['text'];
        $textContent = new TextContent($text);

        // If there are extra fields like annotations, set them
        foreach ($c as $k => $v) {
            if ($k !== 'type' && $k !== 'text') {
                $textContent->$k = $v;
            }
        }

        return $textContent;
    }

    private static function createImageContent(array $c): ImageContent {
        if (!isset($c['data']) || !isset($c['mimeType'])) {
            throw new \InvalidArgumentException('ImageContent requires data and mimeType');
        }
        $imageContent = new ImageContent(data: $c['data'], mimeType: $c['mimeType']);

        // Extra fields
        foreach ($c as $k => $v) {
            if (!in_array($k, ['type', 'data', 'mimeType'], true)) {
                $imageContent->$k = $v;
            }
        }

        return $imageContent;
    }

    private static function createModelPreferences(array $mp): ModelPreferences {
        $modelPreferences = new ModelPreferences(
            costPriority: $mp['costPriority'] ?? null,
            speedPriority: $mp['speedPriority'] ?? null,
            intelligencePriority: $mp['intelligencePriority'] ?? null
        );

        if (isset($mp['hints']) && is_array($mp['hints'])) {
            foreach ($mp['hints'] as $hintData) {
                $modelHint = new ModelHint(
                    name: $hintData['name'] ?? null
                );
                $modelPreferences->addHint($modelHint);
            }
        }

        // If ModelPreferences supports extra fields:
        foreach ($mp as $k => $v) {
            if (!in_array($k, ['costPriority', 'speedPriority', 'intelligencePriority', 'hints'], true)) {
                $modelPreferences->$k = $v;
            }
        }

        return $modelPreferences;
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