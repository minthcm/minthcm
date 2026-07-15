<?php

/**
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM,
 * Copyright (C) 2018-2025 MintHCM
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $app_strings;

$dashletMeta['MCPDocumentationDashlet'] = [
    'module' => 'MCPDocumentation',
    'title' => translate('LBL_HOMEPAGE_TITLE', 'MCPDocumentation'),
    'description' => 'A customizable view into MCP Documentation entries',
    'category' => 'Module Views',
];
