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
$dictionary['CompetencyRatings'] = array(
    'table' => 'competencyratings',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'rating' => array(
            'required' => true,
            'name' => 'rating',
            'vname' => 'LBL_RATING',
            'type' => 'enum',
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
            'len' => 100,
            'size' => '20',
            'options' => 'rating_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'competencies' => array(
            'name' => 'competencies',
            'type' => 'link',
            'relationship' => 'competencyratings_competencies',
            'source' => 'non-db',
            'module' => 'Competencies',
            'bean_name' => 'Competencies',
            'vname' => 'LBL_COMPETENCIES',
            'id_name' => 'competency_id',
        ),
        'competency_name' => array(
            'name' => 'competency_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_COMPETENCY_NAME',
            'save' => true,
            'id_name' => 'competency_id',
            'link' => 'competencies',
            'table' => 'competencies',
            'module' => 'Competencies',
            'rname' => 'name',
            'required' => true,
        ),
        'competency_id' => array(
            'name' => 'competency_id',
            'type' => 'link',
            'relationship' => 'competencyratings_competencies',
            'reportable' => false,
            'vname' => 'LBL_COMPETENCY_ID',
            'dbType' => 'id',
        ),
        'parent_type' => array(
            'name' => 'parent_type',
            'vname' => 'LBL_PARENT_TYPE',
            'type' => 'parent_type',
            'dbType' => 'varchar',
            'group' => 'parent_name',
            'options' => 'competency_ratings_type_list',
            'len' => 255,
        ),
        'parent_name' => array(
            'name' => 'parent_name',
            'parent_type' => 'record_type_display',
            'type_name' => 'parent_type',
            'id_name' => 'parent_id',
            'vname' => 'LBL_PARENT_NAME',
            'type' => 'parent',
            'group' => 'parent_name',
            'source' => 'non-db',
            'options' => 'competency_ratings_type_list',
            'required' => true,
        ),
        'parent_id' => array(
            'name' => 'parent_id',
            'vname' => 'LBL_PARENT_ID',
            'type' => 'id',
            'group' => 'parent_name',
            'reportable' => false,
        ),
    ),
    'relationships' => array(
        'competencyratings_competencies' => array(
            'lhs_module' => 'Competencies',
            'lhs_table' => 'competencies',
            'lhs_key' => 'id',
            'rhs_module' => 'CompetencyRatings',
            'rhs_table' => 'competencyratings',
            'rhs_key' => 'competency_id',
            'relationship_type' => 'one-to-many',
        ),
        'competencyratings_employee' => array(
            'lhs_module' => 'Employees',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'CompetencyRatings',
            'rhs_table' => 'competencyratings',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Employees',
        ),
        'competencyratings_positions' => array(
            'lhs_module' => 'Positions',
            'lhs_table' => 'positions',
            'lhs_key' => 'id',
            'rhs_module' => 'CompetencyRatings',
            'rhs_table' => 'competencyratings',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Positions',
        ),
        'competencyratings_roles' => array(
            'lhs_module' => 'EmployeeRoles',
            'lhs_table' => 'employeeroles',
            'lhs_key' => 'id',
            'rhs_module' => 'CompetencyRatings',
            'rhs_table' => 'competencyratings',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'EmployeeRoles',
        ),
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('CompetencyRatings', 'CompetencyRatings', array('basic', 'assignable', 'security_groups', 'employee_related'));
