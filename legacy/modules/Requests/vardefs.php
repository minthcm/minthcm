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
 * Copyright (C) 2018-2019 MintHCM
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

$dictionary['Requests'] = array(
    'table' => 'requests',
    'audited' => true,
    'activity_enabled' => false,
    'duplicate_merge' => true,
    'fields' => array(
        'status' => array(
            'required' => false,
            'audited' => true,
            'importable' => false,
            'calculated' => false,
            'unified_search' => false,
            'reportable' => true,
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'default' => 'new',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'merge_filter' => 'disabled',
            'len' => 100,
            'size' => '20',
            'options' => 'requests_status_list',
            'studio' => 'visible',
        ),
        'type' => array(
            'required' => false,
            'audited' => true,
            'importable' => false,
            'calculated' => false,
            'unified_search' => false,
            'reportable' => true,
            'name' => 'type',
            'vname' => 'LBL_TYPE',
            'type' => 'enum',
            'default' => '',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'merge_filter' => 'disabled',
            'len' => 100,
            'size' => '20',
            'options' => '',
            'studio' => 'visible',
            'function' => [
                'name' => 'getDictionary',
                'additional_params' => 'Requests-type',
                'include' => 'include/utils/getDictionary.php'
            ],
        ),
        'comments' => array(
            'name' => 'comments',
            'type' => 'link',
            'relationship' => 'requests_comments',
            'module' => 'Comments',
            'bean_name' => 'Comments',
            'source' => 'non-db',
            'vname' => 'LBL_COMMENTS',
        ),
        'comments_widget' => array(
            'name' => 'comments_widget',
            'vname' => 'LBL_COMMENTS_WIDGET',
            'type' => 'function',
            'source' => 'non-db',
            'massupdate' => 0,
            'studio' => 'visible',
            'importable' => 'false',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'audited' => false,
            'reportable' => false,
            'inline_edit' => 0,
            'function' => array(
                'name' => 'display_comments',
                'returns' => 'html',
                'include' => 'modules/Comments/RelatedComments.php',
            ),
        ),
        'training' => array(
            'name' => 'training',
            'type' => 'link',
            'relationship' => 'training_requests',
            'source' => 'non-db',
            'module' => 'Trainings',
            'bean_name' => 'Trainings',
            'vname' => 'LBL_TRAINING',
            'label' => 'LBL_TRAINING',
            'id_link' => 'training_id',
        ),
        'training_id' => array(
            'name' => 'training_id',
            'relationship' => 'training_requests',
            'type' => 'id',
            'vname' => 'LBL_TRAINING_ID',
            'label' => 'LBL_TRAINING_ID',
            'audited' => false,
            'importable' => 'true',
            'reportable' => true,
            'rname' => 'id',
            'isnull' => 'true',
        ),
        'training_name' => array(
            'name' => 'training_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_TRAINING_NAME',
            'label' => 'LBL_TRAINING_NAME',
            'id_name' => 'training_id',
            'link' => 'training',
            'join_name' => 'training',
            'module' => 'Trainings',
            'table' => 'trainings',
            'rname' => 'name',
            'importable' => 'true',
            'reportable' => true,
            'audited' => true,
            'size' => 30,
            'vt_dependency' => "equals(\$type, 'request_training')",
        ),
        'benefit' => array(
            'name' => 'benefit',
            'type' => 'link',
            'relationship' => 'benefit_requests',
            'source' => 'non-db',
            'module' => 'Benefits',
            'bean_name' => 'Benefits',
            'vname' => 'LBL_BENEFIT',
            'label' => 'LBL_BENEFIT',
            'id_link' => 'benefit_id',
        ),
        'benefit_id' => array(
            'name' => 'benefit_id',
            'relationship' => 'benefit_requests',
            'type' => 'id',
            'vname' => 'LBL_BENEFIT_ID',
            'label' => 'LBL_BENEFIT_ID',
            'audited' => false,
            'importable' => 'true',
            'reportable' => true,
            'rname' => 'id',
            'isnull' => 'true',
        ),
        'benefit_name' => array(
            'name' => 'benefit_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_BENEFIT_NAME',
            'label' => 'LBL_BENEFIT_NAME',
            'id_name' => 'benefit_id',
            'link' => 'benefit',
            'join_name' => 'benefit',
            'module' => 'Benefits',
            'table' => 'benefits',
            'rname' => 'name',
            'importable' => 'true',
            'reportable' => true,
            'audited' => true,
            'size' => 30,
            'vt_dependency' => "equals(\$type, 'request_benefit')",
        ),
    ),
    'relationships' => array(
        'requests_comments' => array(
            'lhs_module' => 'Requests',
            'lhs_table' => 'requests',
            'lhs_key' => 'id',
            'rhs_module' => 'Comments',
            'rhs_table' => 'comments',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Requests',
        ),
        'benefit_requests' => array(
            'lhs_module' => 'Benefits',
            'lhs_table' => 'benefits',
            'lhs_key' => 'id',
            'rhs_module' => 'Requests',
            'rhs_table' => 'requests',
            'rhs_key' => 'benefit_id',
            'relationship_type' => 'one-to-many',
        ),
        'training_requests' => array(
            'lhs_module' => 'Trainings',
            'lhs_table' => 'trainings',
            'lhs_key' => 'id',
            'rhs_module' => 'Requests',
            'rhs_table' => 'requests',
            'rhs_key' => 'training_id',
            'relationship_type' => 'one-to-many',
        ),
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);

if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('Requests', 'Requests', array('basic', 'assignable', 'security_groups', 'employee_related'));
