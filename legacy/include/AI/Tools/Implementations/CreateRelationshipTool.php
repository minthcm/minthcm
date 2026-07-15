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

class CreateRelationshipTool extends AbstractTool implements ToolInterface
{
    public function getName(): string
    {
        return 'create_relationship';
    }

    public function getSchema(): ToolSchema
    {
        return new ToolSchema(
            name: 'create_relationship',
            description: 'Create a link between existing records. Get valid relationship_name values from get_module_fields(module).linkable_relationships. This tool is only for link relationships; use create_record or update_record for manipulating direct module fields.',
            parameters: [
                'type' => 'object',
                'properties' => [
                    'module_name' => ['type' => 'string', 'description' => 'Name of the source module (the side from which the relationship is loaded).'],
                    'record_id' => ['type' => 'string', 'description' => 'ID of the source record in module_name.'],
                    'relationship_name' => ['type' => 'string', 'description' => 'Link field name on the source module. Use get_module_fields(module).linkable_relationships[].relationship_name.'],
                    'related_id' => [
                        'type' => 'string',
                        'description' => 'ID of the related record to link.',
                    ],
                    'additional_values' => [
                        'type' => ['object', 'array'],
                        'anyOf' => [
                            ['type' => 'object', 'additionalProperties' => true],
                            ['type' => 'array', 'items' => ['type' => 'string'], 'maxItems' => 0],
                        ],
                        'description' => 'Optional extra column values for join-table relationships (key-value).',
                    ],
                ],
                'required' => ['module_name', 'record_id', 'relationship_name', 'related_id']
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
        if (array_key_exists('relationship_name', $params)) {
            $args['relationship_name'] = (string) $params['relationship_name'];
        }
        if (array_key_exists('related_id', $params)) {
            $args['related_id'] = (string) $params['related_id'];
        }
        if (array_key_exists('additional_values', $params)) {
            $args['additional_values'] = $params['additional_values'];
        }
        return $this->createRelationship(...$args);
    }

    public function createRelationship(
        string $module_name,
        string $record_id,
        string $relationship_name,
        string $related_id,
        object|array $additional_values = [],
    ): ToolResult {
        try {
            $normalized_related_id = trim($related_id);
            $additional = (array) $additional_values;

            ToolValidation::validateMany([
                ToolValidation::make($module_name, 'module_name')->required()->string(),
                ToolValidation::make($record_id, 'record_id')->required()->string(),
                ToolValidation::make($relationship_name, 'relationship_name')->required()->string(),
                ToolValidation::make($normalized_related_id, 'related_id')->required()->string(),
            ]);

            $this->checkPermissions($module_name, 'edit');

            $result = $this->performLink($module_name, $record_id, $relationship_name, $normalized_related_id, $additional);

            return $this->successResult($this->buildMessage($result), $result);
        } catch (\Throwable $e) {
            return $this->handleExecutionException($e);
        }
    }

    /**
     * @param string               $related_id
     * @param array<string, mixed> $additional_values
     *
     * @return array{module: string, record_id: string, relationship: string, status: string}
     */
    private function performLink(
        string $module_name,
        string $record_id,
        string $relationship_name,
        string $related_id,
        array $additional_values
    ): array {
        $bean = \BeanFactory::getBean($module_name, $record_id);
        if (!$bean || empty($bean->id)) {
            throw new \RuntimeException("Record with ID {$record_id} not found in module {$module_name}.");
        }

        $field_defs = (array) ($bean->field_defs ?? []);
        $rel_def = $field_defs[$relationship_name] ?? null;
        if (!is_array($rel_def) || ($rel_def['type'] ?? null) !== 'link') {
            throw new \InvalidArgumentException(
                "Relationship '{$relationship_name}' is not a link field on module '{$module_name}'. Use get_module_fields to discover linkable modules and relationship names."
            );
        }
        if (($rel_def['source'] ?? null) !== 'non-db') {
            throw new \InvalidArgumentException(
                "Relationship '{$relationship_name}' is not supported by create_relationship. Use create_record/update_record for direct DB fields."
            );
        }
        $relationship_def_name = (string) ($rel_def['relationship'] ?? '');
        if ($relationship_def_name === '') {
            throw new \InvalidArgumentException(
                "Relationship '{$relationship_name}' has no relationship definition and cannot be created with create_relationship."
            );
        }

        $related_module = $rel_def['module'] ?? $rel_def['bean_name'] ?? null;
        if (is_string($related_module) && $related_module !== '') {
            $this->checkPermissions($related_module, 'view');
        }

        if (!$bean->load_relationship($relationship_name)) {
            throw new \RuntimeException("Failed to load relationship '{$relationship_name}' on module '{$module_name}'.");
        }

        if ($related_module === null && method_exists($bean->{$relationship_name}, 'getRelatedModuleName')) {
            $resolved = $bean->{$relationship_name}->getRelatedModuleName();
            $related_module = $resolved ? (string) $resolved : null;
        }
        if (!is_string($related_module) || $related_module === '') {
            throw new \InvalidArgumentException(
                "Relationship '{$relationship_name}' has unresolved related module. Use get_module_fields and choose a relationship from linkable_relationships."
            );
        }

        $existing = $bean->{$relationship_name}->get();
        if (!is_array($existing)) {
            $existing = [];
        }
        $existing_map = array_fill_keys(array_map('strval', $existing), true);

        $status = 'no_action';
        if (isset($existing_map[$related_id])) {
            $status = 'link_already_existed';
        } else {
            $add_result = $bean->{$relationship_name}->add($related_id, $additional_values);
            if ($add_result === true || (is_array($add_result) && in_array($related_id, $add_result, true))) {
                $status = 'link_created';
            } else {
                $status = 'link_creation_failed';
            }
        }

        return [
            'module' => $module_name,
            'record_id' => $record_id,
            'relationship' => $relationship_name,
            'status' => $status,
        ];
    }

    /**
     * @param array{status: string} $result
     */
    private function buildMessage(array $result): string
    {
        return match ($result['status']) {
            'link_created' => 'Link created.',
            'link_already_existed' => 'Link already existed.',
            'link_creation_failed' => 'Link creation failed.',
            default => 'No relationship links were processed.',
        };
    }
}
