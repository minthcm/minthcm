<?php

namespace MintHCM\AI\Tools;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

use MintHCM\AI\Tools\Implementations\BrowseDocumentationTool;
use MintHCM\AI\Tools\Implementations\CountRecordsTool;
use MintHCM\AI\Tools\Implementations\CreateRecordTool;
use MintHCM\AI\Tools\Implementations\CreateRelationshipTool;
use MintHCM\AI\Tools\Implementations\DeleteRecordTool;
use MintHCM\AI\Tools\Implementations\DeleteRelationshipTool;
use MintHCM\AI\Tools\Implementations\ExecuteKReportTool;
use MintHCM\AI\Tools\Implementations\GetDocumentationEntryTool;
use MintHCM\AI\Tools\Implementations\GetKReportDetailsTool;
use MintHCM\AI\Tools\Implementations\GetModuleFieldsTool;
use MintHCM\AI\Tools\Implementations\GetModuleNamesTool;
use MintHCM\AI\Tools\Implementations\ListKReportsTool;
use MintHCM\AI\Tools\Implementations\ListRelationshipsTool;
use MintHCM\AI\Tools\Implementations\SearchRecordsTool;
use MintHCM\AI\Tools\Implementations\SumRecordsTool;
use MintHCM\AI\Tools\Implementations\UpdateRecordTool;

/**
 * Default catalog of agent tools. Each entry is a concrete implementation of
 * ToolInterface living in `MintHCM\AI\Tools\Implementations`. The MCP server
 * exposes the same set by delegating to the very same classes.
 */
class DefaultToolRegistryFactory
{
    /**
     * @return class-string<ToolInterface>[]
     */
    public static function catalog(): array
    {
        return [
            // Documentation — encourage the agent to consult /SKILL.md style
            // documentation before calling other tools (mcp/SKILL.md flow).
            BrowseDocumentationTool::class,
            GetDocumentationEntryTool::class,

            // Module / field metadata
            GetModuleNamesTool::class,
            GetModuleFieldsTool::class,

            // Record CRUD + querying
            SearchRecordsTool::class,
            CountRecordsTool::class,
            SumRecordsTool::class,
            CreateRecordTool::class,
            UpdateRecordTool::class,
            DeleteRecordTool::class,

            // Relationships
            ListRelationshipsTool::class,
            CreateRelationshipTool::class,
            DeleteRelationshipTool::class,

            // KReports
            ListKReportsTool::class,
            GetKReportDetailsTool::class,
            ExecuteKReportTool::class,
        ];
    }

    public static function create(): ToolRegistry
    {
        $registry = new ToolRegistry();
        foreach (self::catalog() as $class) {
            $registry->register(new $class());
        }
        return $registry;
    }
}
