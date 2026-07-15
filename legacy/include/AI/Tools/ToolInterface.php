<?php

namespace MintHCM\AI\Tools;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

interface ToolInterface
{
    /**
     * Unique identifier exposed to the model in the tool schema.
     */
    public function getName(): string;

    /**
     * Schema sent to the model alongside other available tools.
     */
    public function getSchema(): ToolSchema;

    /**
     * Executes the tool with arguments supplied by the model.
     *
     * Implementations MUST NOT throw on business errors — they should return a failing
     * ToolResult so that the agent loop can forward the error to the model and keep
     * iterating. Hard exceptions are reserved for genuinely unexpected conditions and
     * will be logged as ERROR steps by the agent.
     *
     * @param array  $params       Arguments produced by the model (associative array).
     */
    public function execute(array $params): ToolResult;
}
