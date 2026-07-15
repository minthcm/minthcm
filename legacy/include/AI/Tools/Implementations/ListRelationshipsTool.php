<?php

namespace MintHCM\AI\Tools\Implementations;

use MintHCM\AI\Tools\AbstractTool;
use MintHCM\AI\Tools\ToolInterface;
use MintHCM\AI\Tools\ToolSchema;
use MintHCM\AI\Tools\ToolResult;
use MintHCM\AI\Tools\Utils\ToolValidation;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class ListRelationshipsTool extends AbstractTool implements ToolInterface
{
    public function getName(): string
    {
        return 'list_relationships';
    }

    public function getSchema(): ToolSchema
    {
        return new ToolSchema(
            name: 'list_relationships',
            description: 'Get concrete linked records for one source record. Returns relationship_name, related_module, and linked record IDs.',
            parameters: [
                'type' => 'object',
                'properties' => [
                    'module_name' => ['type' => 'string', 'description' => 'Name of the source module.'],
                    'record_id' => ['type' => 'string', 'description' => 'ID of the source record to inspect relationships for.'],
                ],
                'required' => ['module_name', 'record_id']
            ],
        );
    }

    public function execute(array $params): ToolResult
    {
        $args = [];
        if (array_key_exists('module_name', $params)) {
            $args['module_name'] = (string) $params['module_name'];
        }
        if (array_key_exists('record_id', $params)) {
            $args['record_id'] = (string) $params['record_id'];
        }
        return $this->listRelationships(...$args);
    }

    public function listRelationships(string $module_name, string $record_id): ToolResult
    {
        try {
            ToolValidation::validateMany([
                ToolValidation::make($module_name, 'module_name')->required()->string(),
                ToolValidation::make($record_id, 'record_id')->required()->string(),
            ]);

            $this->checkPermissions($module_name, 'view');

            $connections = $this->collectConnections($module_name, $record_id);

            return $this->successResult(
                count($connections) > 0
                    ? "Concrete connections for record '{$record_id}' in module '{$module_name}'."
                    : "No connections found for record '{$record_id}' in module '{$module_name}'.",
                [
                    'module' => $module_name,
                    'record_id' => $record_id,
                    'connections' => $connections,
                ]
            );
        } catch (\Throwable $e) {
            return $this->handleExecutionException($e);
        }
    }

    /**
     * @return list<array{relationship_name: string, related_module: string, linked_record_ids: list<string>}>
     */
    private function collectConnections(string $module_name, string $record_id): array
    {
        $bean = \BeanFactory::getBean($module_name, $record_id);
        if (!$bean || empty($bean->field_defs)) {
            throw new \RuntimeException("Record '{$record_id}' in module '{$module_name}' not found or not accessible.");
        }

        $result = [];
        foreach ($bean->field_defs as $field_name => $def) {
            $def_arr = (array) $def;
            if (($def_arr['type'] ?? '') !== 'link') {
                continue;
            }

            if (!$bean->load_relationship($field_name) || empty($bean->{$field_name})) {
                continue;
            }

            $related_module = $def_arr['module'] ?? $def_arr['bean_name'] ?? null;
            if ($related_module === null && method_exists($bean->{$field_name}, 'getRelatedModuleName')) {
                $related_module = $bean->{$field_name}->getRelatedModuleName() ?: null;
            }
            if (!is_string($related_module) || $related_module === '') {
                continue;
            }

            $related_ids = $bean->{$field_name}->get();
            if (!is_array($related_ids) || $related_ids === []) {
                continue;
            }
            $normalized_ids = array_values(array_unique(array_map('strval', $related_ids)));

            $result[] = [
                'relationship_name' => (string) $field_name,
                'related_module' => (string) $related_module,
                'linked_record_ids' => $normalized_ids,
            ];
        }

        return $result;
    }
}
