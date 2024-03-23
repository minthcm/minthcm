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
 * Copyright (C) 2018-2023 MintHCM
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
$dictionary['Appraisals'] = array(
    'table' => 'appraisals',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'date' =>
        array(
            'required' => false,
            'name' => 'date',
            'vname' => 'LBL_DATE',
            'type' => 'date',
            'massupdate' => '1',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'inline_edit' => '',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
            'dbType' => 'datetime',
        ),
        'status' =>
        array(
            'required' => true,
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'massupdate' => '1',
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'inline_edit' => '',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 100,
            'size' => '20',
            'options' => 'appraisals_status_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'type' =>
        array(
            'required' => true,
            'name' => 'type',
            'vname' => 'LBL_TYPE',
            'type' => 'enum',
            'massupdate' => '1',
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'inline_edit' => '',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 100,
            'size' => '20',
            'options' => 'appraisals_type_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'meetings' =>
        array(
            'name' => 'meetings',
            'type' => 'link',
            'relationship' => 'appraisals_meetings',
            'source' => 'non-db',
            'module' => 'Meetings',
            'bean_name' => 'Meeting',
            'vname' => 'LBL_MEETINGS',
        ),
        'documents' =>
        array(
            'name' => 'documents',
            'type' => 'link',
            'relationship' => 'appraisals_documents',
            'source' => 'non-db',
            'module' => 'Documents',
            'bean_name' => 'Document',
            'vname' => 'LBL_DOCUMENTS',
        ),
        'roles' =>
        array(
            'name' => 'roles',
            'type' => 'link',
            'relationship' => 'appraisals_roles',
            'source' => 'non-db',
            'module' => 'EmployeeRoles',
            'bean_name' => 'EmployeeRoles',
            'vname' => 'LBL_ROLES',
        ),
        'candidatures' => array(
            'name' => 'candidatures',
            'type' => 'link',
            'relationship' => 'candidatures_appraisals',
            'source' => 'non-db',
            'module' => 'Candidatures',
            'bean_name' => 'Candidatures',
            'vname' => 'LBL_CANDIDATURES',
            'id_name' => 'candidature_id',
        ),
        'candidature_name' => array(
            'name' => 'candidature_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_CANDIDATURE_NAME',
            'save' => true,
            'id_name' => 'candidature_id',
            'link' => 'candidatures',
            'table' => 'candidatures',
            'module' => 'Candidatures',
            'rname' => 'name',
        ),
        'candidature_id' => array(
            'name' => 'candidature_id',
            'type' => 'id',
            'relationship' => 'candidatures_appraisals',
            'reportable' => false,
            'rname' => 'id',
            'isnull' => 'true',
            'dbType' => 'id',
            'vname' => 'LBL_CANDIDATURE_ID',
        ),
        'positions' => array(
            'name' => 'positions',
            'type' => 'link',
            'relationship' => 'positions_appraisals',
            'source' => 'non-db',
            'module' => 'Positions',
            'bean_name' => 'Positions',
            'vname' => 'LBL_POSITIONS',
            'id_name' => 'position_id',
        ),
        'position_name' => array(
            'name' => 'position_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_POSITION_NAME',
            'save' => true,
            'id_name' => 'position_id',
            'link' => 'positions',
            'table' => 'positions',
            'module' => 'Positions',
            'rname' => 'name',
        ),
        'position_id' => array(
            'name' => 'position_id',
            'type' => 'id',
            'relationship' => 'positions_appraisals',
            'reportable' => false,
            'rname' => 'id',
            'isnull' => 'true',
            'dbType' => 'id',
            'vname' => 'LBL_POSITION_ID',
        ),
        'evaluators' => array(
            'name' => 'evaluators',
            'type' => 'link',
            'relationship' => 'appraisals_employees_evaluations',
            'source' => 'non-db',
            'module' => 'Employees',
            'bean_name' => 'Employees',
            'vname' => 'LBL_EMPLOYEES',
            'id_name' => 'employee_id',
        ),
        'evaluator_name' => array(
            'name' => 'evaluator_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_EVALUATOR_NAME',
            'save' => true,
            'id_name' => 'evaluator_id',
            'link' => 'evaluators',
            'table' => 'users',
            'module' => 'Employees',
            'rname' => 'name',
            'required' => true,
        ),
        'evaluator_id' => array(
            'name' => 'evaluator_id',
            'type' => 'id',
            'relationship' => 'appraisals_employees_evaluations',
            'reportable' => false,
            'rname' => 'id',
            'isnull' => 'true',
            'dbType' => 'id',
            'vname' => 'LBL_EVALUATOR_ID',
        ),
        'appraisalitems' => array(
            'name' => 'appraisalitems',
            'type' => 'link',
            'relationship' => 'appraisals_appraisalitems',
            'source' => 'non-db',
            'module' => 'AppraisalItems',
            'bean_name' => 'AppraisalItems',
            'vname' => 'LBL_APPRAISALITEMS',
        ),
        'appraisal_items_inline' =>
        array(
            'required' => false,
            'name' => 'appraisal_items_inline',
            'vname' => 'LBL_APPRAISAL_ITEMS_INLINE',
            'type' => 'function',
            'source' => 'non-db',
            'massupdate' => 0,
            'importable' => 'false',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'audited' => false,
            'reportable' => false,
            'inline_edit' => false,
            'function' =>
            array(
                'name' => 'displayInlineAppraisalItems',
                'returns' => 'html',
                'include' => 'modules/AppraisalItems/LineItems/LineItems.php'
            ),
        ),
    ),
    'relationships' => array(
        "candidatures_appraisals" => array(
            'lhs_module' => 'Candidatures',
            'lhs_table' => 'candidatures',
            'lhs_key' => 'id',
            'rhs_module' => 'Appraisals',
            'rhs_table' => 'appraisals',
            'rhs_key' => 'candidature_id',
            'relationship_type' => 'one-to-many',
        ),
        "positions_appraisals" => array(
            'lhs_module' => 'Positions',
            'lhs_table' => 'positions',
            'lhs_key' => 'id',
            'rhs_module' => 'Appraisals',
            'rhs_table' => 'appraisals',
            'rhs_key' => 'position_id',
            'relationship_type' => 'one-to-many',
        ),
        "appraisals_employees_evaluations" => array(
            'lhs_module' => 'Employees',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'Appraisals',
            'rhs_table' => 'appraisals',
            'rhs_key' => 'evaluator_id',
            'relationship_type' => 'one-to-many',
        ),
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('Appraisals', 'Appraisals',
    array('basic', 'assignable', 'employee_related', 'security_groups'));

$dictionary["Appraisals"]["fields"]['employee_name']['vt_required']      = "equals(\$type,'other')";
$dictionary["Appraisals"]["fields"]['candidature_name']['vt_dependency'] = "not(equals(\$type,'other'))";
$dictionary["Appraisals"]["fields"]['candidature_name']['vt_required']   = "equals(\$type,'recruiting')";
