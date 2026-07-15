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

class DeleteRelationshipTool extends AbstractTool implements ToolInterface
{
    public function getName(): string
    {
        return 'delete_relationship';
    }

    public function getSchema(): ToolSchema
    {
        return new ToolSchema(
            name: 'delete_relationship',
            description: 'Delete a link between existing records. Get valid relationship_name values from get_module_fields(module).linkable_relationships. This tool is only for link relationships.',
            parameters: [
                'type' => 'object',
                'properties' => [
                    'module_name' => ['type' => 'string', 'description' => 'Name of the source module (the side from which the relationship is loaded).'],
                    'record_id' => ['type' => 'string', 'description' => 'ID of the source record in module_name.'],
                    'relationship_name' => ['type' => 'string', 'description' => 'Link field name on the source module. Use get_module_fields(module).linkable_relationships[].relationship_name.'],
                    'related_id' => ['type' => 'string', 'description' => 'ID of the related record to unlink.'],
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
        return $this->deleteRelationship(...$args);
    }

    public function deleteRelationship(
        string $module_name,
        string $record_id,
        string $relationship_name,
        string $related_id
    ): ToolResult {
        try {
            $normalized_related_id = trim($related_id);

            ToolValidation::validateMany([
                ToolValidation::make($module_name, 'module_name')->required()->string(),
                ToolValidation::make($record_id, 'record_id')->required()->string(),
                ToolValidation::make($relationship_name, 'relationship_name')->required()->string(),
                ToolValidation::make($normalized_related_id, 'related_id')->required()->string(),
            ]);

            $this->checkPermissions($module_name, 'edit');

            $result = $this->performUnlink($module_name, $record_id, $relationship_name, $normalized_related_id);

            return $this->successResult($this->buildMessage($result), $result);
        } catch (\Throwable $e) {
            return $this->handleExecutionException($e);
        }
    }

    /**
     * @return array{module: string, record_id: string, relationship: string, status: string}
     */
    private function performUnlink(
        string $module_name,
        string $record_id,
        string $relationship_name,
        string $related_id
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
                "Relationship '{$relationship_name}' is not supported by delete_relationship. Use update_record for direct DB fields."
            );
        }

        $related_module = $rel_def['module'] ?? $rel_def['bean_name'] ?? null;

        if (!$bean->load_relationship($relationship_name)) {
            throw new \RuntimeException("Failed to load relationship '{$relationship_name}' on module '{$module_name}'.");
        }

        if ($related_module === null && method_exists($bean->{$relationship_name}, 'getRelatedModuleName')) {
            $resolved = $bean->{$relationship_name}->getRelatedModuleName();
            $related_module = $resolved ? (string) $resolved : null;
        }
        if (is_string($related_module) && $related_module !== '') {
            $this->checkPermissions($related_module, 'view');
        }

        $existing = $bean->{$relationship_name}->get();
        if (!is_array($existing)) {
            $existing = [];
        }
        $existing_map = array_fill_keys(array_map('strval', $existing), true);

        $status = 'no_action';
        if (!isset($existing_map[$related_id])) {
            $status = 'link_not_found';
        } else {
            $remove_result = $bean->{$relationship_name}->remove($related_id);
            $status = ($remove_result === false) ? 'link_removal_failed' : 'link_removed';
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
            'link_removed' => 'Link removed.',
            'link_removal_failed' => 'Link removal failed.',
            'link_not_found' => 'Link did not exist.',
            default => 'No links were processed.',
        };
    }
}
