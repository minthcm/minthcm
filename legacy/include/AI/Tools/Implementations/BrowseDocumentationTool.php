<?php

namespace MintHCM\AI\Tools\Implementations;

use MintHCM\AI\Tools\AbstractTool;
use MintHCM\AI\Tools\ToolInterface;
use MintHCM\AI\Tools\ToolSchema;
use MintHCM\AI\Tools\ToolResult;
use MintHCM\AI\Tools\Traits\PaginationTrait;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class BrowseDocumentationTool extends AbstractTool implements ToolInterface
{
    use PaginationTrait;

    public function getName(): string
    {
        return 'browse_documentation';
    }

    private const BASE_DESCRIPTION = 'Browse MintHCM documentation entries by category. Use get_documentation_entry to read a specific entry.';

    public function getSchema(): ToolSchema
    {
        try {
            $extras = $this->buildCategorySchemaExtras(self::BASE_DESCRIPTION);
        } catch (\Throwable) {
            $extras = ['description' => self::BASE_DESCRIPTION, 'enum' => []];
        }

        $category_property = ['type' => 'string', 'description' => 'Documentation category name.'];
        if ($extras['enum'] !== []) {
            $category_property['enum'] = $extras['enum'];
        }

        return new ToolSchema(
            name: 'browse_documentation',
            description: $extras['description'],
            parameters: [
                'type'       => 'object',
                'properties' => [
                    'category' => $category_property,
                    'offset'   => self::PAGINATION_OFFSET,
                    'limit'    => self::PAGINATION_LIMIT,
                ],
                'required' => ['category'],
            ],
        );
    }

    /**
     * Builds the dynamic part of the schema: enriched description and enum values for the
     * category parameter. Caller is responsible for providing a legacy context.
     *
     * @return array{description: string, enum: list<string>}
     */
    public function buildCategorySchemaExtras(string $base_description): array
    {
        $categories = $this->fetchAllCategories();

        $enum_names = [];
        foreach ($categories as $row) {
            $name = trim((string) ($row['name'] ?? ''));
            if ($name !== '') {
                $enum_names[] = $name;
            }
        }
        $enum_names = array_values(array_unique($enum_names, SORT_STRING));

        return [
            'description' => $base_description . "\n\n" . $this->categoriesListText($categories),
            'enum'        => $enum_names,
        ];
    }

    public function execute(array $params): ToolResult
    {
        $args = [];
        if (array_key_exists('category', $params)) {
            $args['category'] = (string) $params['category'];
        }
        if (array_key_exists('offset', $params)) {
            $args['offset'] = (int) $params['offset'];
        }
        if (array_key_exists('limit', $params)) {
            $args['limit'] = (int) $params['limit'];
        }
        return $this->browseDocumentation(...$args);
    }

    public function browseDocumentation(string $category, int $offset = 0, int $limit = -1): ToolResult
    {
        try {
            $this->checkPermissions('MCPDocumentation');
            $this->checkPermissions('MCPDocCategories');

            $normalized_category = trim($category);

            if ($normalized_category === '') {
                $hint = $this->categoriesListText($this->fetchAllCategories());
                return $this->errorResult("Parameter 'category' cannot be empty. {$hint}");
            }

            [$resolved_offset, $resolved_limit] = $this->processPaginationParams($offset, $limit);

            $data = $this->loadBrowseData($normalized_category, $resolved_offset, $resolved_limit);
            if ($data['outcome'] === 'unknown_category') {
                $data['hint'] = $this->categoriesListText($this->fetchAllCategories());
            }

            if ($data['outcome'] === 'unknown_category') {
                return $this->errorResult("Unknown category '{$normalized_category}'. {$data['hint']}");
            }

            $entries = $data['entries'];
            $payload = $this->formatPaginationData($data['list_result'], $resolved_offset, [
                'records_returned' => count($entries),
                'category'         => $normalized_category,
                'entries'          => $entries,
            ], $resolved_limit);

            if ($data['outcome'] === 'empty_category') {
                return $this->successResult("No entries in category '{$normalized_category}'.", $payload);
            }

            return $this->successResult('Documentation entries retrieved successfully.', $payload);
        } catch (\Throwable $e) {
            return $this->handleExecutionException($e);
        }
    }

    /**
     * @return array{outcome: 'unknown_category'|'empty_category'|'ok', entries: list<array{id: string, name: string, category: string}>, list_result: array<string, mixed>}
     */
    private function loadBrowseData(string $normalized_category, int $offset, int $limit): array
    {
        $category_ids = $this->fetchCategoryIdsByName($normalized_category);
        if ($category_ids === []) {
            return ['outcome' => 'unknown_category', 'entries' => [], 'list_result' => []];
        }

        ['entries' => $entries, 'list_result' => $list_result] = $this->fetchDocumentationEntries($category_ids, $normalized_category, $offset, $limit);
        if ($entries === []) {
            // At offset=0 the category is genuinely empty; at offset>0 we've simply gone past the last page
            $outcome = $offset === 0 ? 'empty_category' : 'ok';
            return ['outcome' => $outcome, 'entries' => [], 'list_result' => $list_result];
        }

        return ['outcome' => 'ok', 'entries' => $entries, 'list_result' => $list_result];
    }

    /**
     * @param string[] $category_ids
     * @return array{entries: list<array{id: string, name: string, category: string}>, list_result: array<string, mixed>}
     */
    private function fetchDocumentationEntries(array $category_ids, string $fallback_category_name, int $offset, int $limit): array
    {
        $bean      = \BeanFactory::newBean('MCPDocumentation');
        $table_name = $bean->table_name;
        $quoted_ids = array_map(
            static fn (string $id): string => "'" . $bean->db->quote($id) . "'",
            $category_ids
        );
        $where = sprintf(
            '%s.category_id IN (%s) AND %s.deleted = 0',
            $table_name,
            implode(', ', $quoted_ids),
            $table_name
        );
        $list_result = $bean->get_list('name', $where, $offset, $limit, $this->getMaxPaginationLimit($limit));

        $entries = [];
        foreach (($list_result['list'] ?? []) as $row) {
            $entries[] = [
                'id'       => $row->id,
                'name'     => $row->name,
                'category' => $row->category_name ?: $fallback_category_name,
            ];
        }

        return ['entries' => $entries, 'list_result' => $list_result];
    }

    /**
     * @return array<array{id: string, name: string, description: string}>
     */
    public function fetchAllCategories(): array
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }
        $cat_bean   = \BeanFactory::newBean('MCPDocCategories');
        $cat_table  = $cat_bean->table_name;
        $list_result = $cat_bean->get_list('name', "{$cat_table}.deleted = 0", 0, -1, -1);

        $categories = [];
        foreach (($list_result['list'] ?? []) as $row) {
            $categories[] = [
                'id'          => $row->id,
                'name'        => $row->name,
                'description' => $row->description ?? '',
            ];
        }

        $cache = $categories;
        return $cache;
    }

    /**
     * @return string[]
     */
    private function fetchCategoryIdsByName(string $category): array
    {
        $bean         = \BeanFactory::newBean('MCPDocCategories');
        $table_name    = $bean->table_name;
        $safe_category = $bean->db->quote($category);
        $where_clause  = "LOWER({$table_name}.name) = LOWER('{$safe_category}') AND {$table_name}.deleted = 0";
        $list_result   = $bean->get_list('name', $where_clause);

        $ids = [];
        foreach (($list_result['list'] ?? []) as $row) {
            if (!empty($row->id)) {
                $ids[] = (string) $row->id;
            }
        }

        return $ids;
    }

    /**
     * @param array<array{id: string, name: string, description: string}> $categories
     */
    public function categoriesListText(array $categories): string
    {
        if ($categories === []) {
            return 'No documentation categories are currently available.';
        }

        $pairs = array_map(
            static function (array $category): string {
                $name        = $category['name'] ?? '';
                $description = trim((string) ($category['description'] ?? ''));
                return $description !== '' ? "{$name} - {$description}" : $name;
            },
            $categories
        );

        return "Available categories:\n\n" . implode("\n", $pairs);
    }
}
