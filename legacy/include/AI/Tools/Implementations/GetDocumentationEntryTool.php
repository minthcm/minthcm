<?php

namespace MintHCM\AI\Tools\Implementations;

use MintHCM\AI\Tools\AbstractTool;
use MintHCM\AI\Tools\ToolInterface;
use MintHCM\AI\Tools\ToolSchema;
use MintHCM\AI\Tools\ToolResult;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class GetDocumentationEntryTool extends AbstractTool implements ToolInterface
{
    public function getName(): string
    {
        return 'get_documentation_entry';
    }

    public function getSchema(): ToolSchema
    {
        return new ToolSchema(
            name: 'get_documentation_entry',
            description: 'Retrieve the full content of a MCP documentation entry by its ID. Use browse_documentation first to find the entry ID.',
            parameters: [
                'type' => 'object',
                'properties' => [
                    'id' => ['type' => 'string', 'description' => 'The ID of the documentation entry to retrieve.'],
                ],
                'required' => ['id']
            ],
        );
    }

    public function execute(array $params): ToolResult
    {
        $args = [];
        if (array_key_exists('id', $params)) {
            $args['id'] = (string) $params['id'];
        }
        return $this->getDocumentationEntry(...$args);
    }

    public function getDocumentationEntry(string $id): ToolResult
    {
        try {
            $this->checkPermissions('MCPDocumentation');

            $bean = \BeanFactory::getBean('MCPDocumentation', $id);

            if (!$bean || empty($bean->id) || !empty($bean->deleted)) {
                return $this->errorResult("Documentation entry with ID '{$id}' not found or has been deleted.");
            }

            $entry = [
                'id' => $bean->id,
                'name' => $bean->name,
                'category' => $this->resolveCategoryName($bean),
                'content' => $bean->content,
            ];

            return $this->successResult('Documentation entry retrieved successfully.', $entry);
        } catch (\Throwable $e) {
            return $this->handleExecutionException($e);
        }
    }

    private function resolveCategoryName(object $bean): string
    {
        if (!empty($bean->category_name)) {
            return (string) $bean->category_name;
        }

        if (empty($bean->category_id)) {
            return '';
        }

        $cat_bean = \BeanFactory::getBean('MCPDocCategories', $bean->category_id);

        return ($cat_bean && !empty($cat_bean->id)) ? (string) $cat_bean->name : '';
    }
}
