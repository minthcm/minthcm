<?php

namespace MintHCM\AI\Tools;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class ToolRegistry
{
    /** @var array<string, ToolInterface> */
    private array $tools = [];

    public function register(ToolInterface $tool): void
    {
        $name = $tool->getName();
        if (isset($this->tools[$name]) && isset($GLOBALS['log'])) {
            $GLOBALS['log']->warning('ToolRegistry: overriding tool "' . $name . '"');
        }
        $this->tools[$name] = $tool;
    }

    public function has(string $name): bool
    {
        return isset($this->tools[$name]);
    }

    public function get(string $name): ToolInterface
    {
        if (!isset($this->tools[$name])) {
            throw new ToolNotFoundException('Unknown tool: ' . $name);
        }
        return $this->tools[$name];
    }

    /**
     * @return ToolInterface[]
     */
    public function getAll(): array
    {
        return array_values($this->tools);
    }

    /**
     * @return ToolSchema[]
     */
    public function getSchemas(): array
    {
        return array_map(static fn(ToolInterface $t) => $t->getSchema(), $this->getAll());
    }

    /**
     * Returns schemas for a filtered subset of tools, in the order requested.
     * Unknown names are silently skipped so the caller (agent definition) can be
     * permissive about stale configuration.
     *
     * @param string[] $names
     * @return ToolSchema[]
     */
    public function getSchemasFor(array $names): array
    {
        $schemas = [];
        foreach ($names as $name) {
            if (isset($this->tools[$name])) {
                $schemas[] = $this->tools[$name]->getSchema();
            }
        }
        return $schemas;
    }
}
