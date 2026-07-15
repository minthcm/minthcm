<?php

namespace MintMCP\Capabilities\Tools;

use Mcp\Capability\Attribute\Schema;
use Mcp\Capability\Discovery\SchemaGenerator;
use Mcp\Schema\Result\CallToolResult;
use MintHCM\AI\Tools\Traits\PaginationTrait;
use ReflectionMethod;
use MintHCM\AI\Tools\Implementations\BrowseDocumentationTool as AIBrowseDocumentationTool;

/**
 * Uses manual tool registration in MCPApp::run() (via addTool) with a dynamically built
 * description that lists available documentation categories.
 * Do NOT add #[McpTool] — it would cause duplicate registration via auto-discovery.
 */
final class BrowseDocumentationTool extends AbstractMCPTool
{
    use PaginationTrait;

    private const BROWSE_DOCUMENTATION_TOOL_DESCRIPTION = 'Browse MintHCM documentation entries by category.';

    #[Schema(
        type: 'object',
        properties: [
            'category' => [
                'type' => 'string',
                'description' => 'Documentation category name.',
            ],
            'offset' => self::PAGINATION_OFFSET,
            'limit'  => self::PAGINATION_LIMIT,
        ],
        required: ['category'],
    )]
    public function browseDocumentation(string $category, int $offset = 0, int $limit = -1): CallToolResult
    {
        return $this->delegate(AIBrowseDocumentationTool::class, [
            'category' => $category,
            'offset'   => $offset,
            'limit'    => $limit,
        ]);
    }

    public static function buildManualRegistrationData(SchemaGenerator $schemaGenerator, ReflectionMethod $browseDocumentationMethod): array
    {
        $inputSchema = $schemaGenerator->generate($browseDocumentationMethod);

        try {
            $mcpTool = new self();
            $extras  = $mcpTool->withLegacyContext(function (): array {
                return (new AIBrowseDocumentationTool())->buildCategorySchemaExtras(self::BROWSE_DOCUMENTATION_TOOL_DESCRIPTION);
            });
        } catch (\Throwable $e) {
            logger()->warning('BrowseDocumentationTool::buildManualRegistrationData failed', ['message' => $e->getMessage()]);
            return [
                'description' => self::BROWSE_DOCUMENTATION_TOOL_DESCRIPTION,
                'inputSchema' => $inputSchema,
            ];
        }

        if ($extras['enum'] !== [] && isset($inputSchema['properties']['category']) && is_array($inputSchema['properties']['category'])) {
            $inputSchema['properties']['category'] = array_merge(
                $inputSchema['properties']['category'],
                ['enum' => $extras['enum']],
            );
        }

        return [
            'description' => $extras['description'],
            'inputSchema' => $inputSchema,
        ];
    }
}
