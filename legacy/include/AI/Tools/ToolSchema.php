<?php

namespace MintHCM\AI\Tools;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

readonly class ToolSchema
{
    /**
     * @param array $parameters JSON Schema describing tool arguments.
     *                          Expected shape: ['type' => 'object', 'properties' => [...], 'required' => [...]].
     */
    public function __construct(
        public string $name,
        public string $description,
        public array  $parameters,
    ) {}

    public function toArray(): array
    {
        return [
            'name'        => $this->name,
            'description' => $this->description,
            'parameters'  => $this->normalizeSchema($this->parameters),
        ];
    }

    /**
     * Recursively normalizes JSON Schema nodes so that `properties` is always
     * serialized as a JSON object ({}) rather than a JSON array ([]).
     *
     * PHP encodes an empty array as [] (JSON array). Providers such as Anthropic
     * and OpenRouter reject a tool schema where `properties` is an array — it
     * must be a dictionary / object even when empty.
     */
    private function normalizeSchema(array $schema): array
    {
        if (array_key_exists('properties', $schema)) {
            if ($schema['properties'] === []) {
                $schema['properties'] = new \stdClass();
            } elseif (is_array($schema['properties'])) {
                $normalized = [];
                foreach ($schema['properties'] as $key => $value) {
                    $normalized[$key] = is_array($value) ? $this->normalizeSchema($value) : $value;
                }
                $schema['properties'] = $normalized;
            }
        }

        if (array_key_exists('items', $schema) && is_array($schema['items'])) {
            $schema['items'] = $this->normalizeSchema($schema['items']);
        }

        return $schema;
    }
}
