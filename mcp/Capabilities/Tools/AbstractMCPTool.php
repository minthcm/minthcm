<?php

namespace MintMCP\Capabilities\Tools;

use Mcp\Schema\Result\CallToolResult;
use Mcp\Schema\Content\TextContent;

abstract class AbstractMCPTool
{
    /**
     * Executes callback in legacy context and always switches back.
     *
     * @template T
     *
     * @param callable():T $callback
     *
     * @return T
     */
    protected function withLegacyContext(callable $callback): mixed
    {
        if (!chdir('../legacy')) {
            throw new \RuntimeException('Failed to change directory to legacy context.');
        }
        try {
            return $callback();
        } finally {
            if (!chdir('../mcp')) {
                throw new \RuntimeException('Failed to restore MCP working directory.');
            }
        }
    }

    /**
     * Bridges MCP's CallToolResult API to the shared AI tool implementations.
     *
     * The single source of truth for tool behavior lives in
     * `MintHCM\AI\Tools\Implementations\…`. MCP tool classes are now thin
     * wrappers that:
     *   - declare the `#[McpTool]` + `#[Schema]` attributes for MCP discovery,
     *   - convert their typed PHP parameters into the associative array shape
     *     the AI tool's `execute()` expects,
     *   - call this delegate(), which handles the cwd swap, executes the AI
     *     tool, and adapts the resulting ToolResult back into a CallToolResult.
     *
     * @template T of \MintHCM\AI\Tools\ToolInterface
     * @param class-string<T> $aiToolClass
     * @param array<string, mixed> $params
     */
    protected function delegate(string $aiToolClass, array $params): CallToolResult
    {
        return $this->withLegacyContext(function () use ($aiToolClass, $params): CallToolResult {
            /** @var \MintHCM\AI\Tools\ToolInterface $aiTool */
            $aiTool = new $aiToolClass();
            $result = $aiTool->execute($params);

            if ($result->success) {
                return new CallToolResult(
                    content: [new TextContent($result->output)],
                    isError: false,
                    structuredContent: $result->structuredContent,
                );
            }

            return new CallToolResult(
                content: [new TextContent($result->error ?? $result->output)],
                isError: true,
            );
        });
    }
}
