<?php

/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM,
 * Copyright (C) 2018-2025 MintHCM
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM"
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo.
 * If the display of the logos is not reasonably feasible for technical reasons, the
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */

$dictionary['MCPDocCategories'] = [
    'table' => 'mcp_doc_categories',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'doctrineEntity' => [],
    'fields' => [
        'mcp_documentation' => [
            'name' => 'mcp_documentation',
            'type' => 'link',
            'relationship' => 'mcp_documentation_categories',
            'source' => 'non-db',
            'module' => 'MCPDocumentation',
            'bean_name' => 'MCPDocumentation',
            'vname' => 'LBL_MCP_DOCUMENTATION',
        ],
    ],
    'relationships' => [
        'mcp_documentation_categories' => [
            'lhs_module' => 'MCPDocCategories',
            'lhs_table' => 'mcp_doc_categories',
            'lhs_key' => 'id',
            'rhs_module' => 'MCPDocumentation',
            'rhs_table' => 'mcp_documentation',
            'rhs_key' => 'category_id',
            'relationship_type' => 'one-to-many',
        ],
    ],
    'optimistic_locking' => true,
    'unified_search' => true,
    'unified_search_default_enabled' => true,
];

if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('MCPDocCategories', 'MCPDocCategories', ['basic', 'security_groups']);
