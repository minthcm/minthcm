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
$dictionary['OnboardingOffboardingElements'] = array(
    'table' => 'onboardingoffboardingelements',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'importable' => true,
    'fields' => array(
        'kind_of_element' => array(
            'required' => true,
            'name' => 'kind_of_element',
            'vname' => 'LBL_KIND_OF_ELEMENT',
            'type' => 'enum',
            'massupdate' => '1',
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
            'options' => 'kind_of_element_list',
            'studio' => 'visible',
        ),
        'type' => array(
            'required' => true,
            'name' => 'type',
            'vname' => 'LBL_TYPE',
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
            'options' => 'onoff_elements_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'task_duration_hours' => array(
            'name' => 'task_duration_hours',
            'vname' => 'LBL_TASK_DURATION_HOURS',
            'type' => 'int',
            'len' => '2',
            'comment' => '',
            'required' => true,
            'reportable' => true,
            'audited' => true,
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
        ),
        'task_duration_minutes' => array(
            'name' => 'task_duration_minutes',
            'vname' => 'LBL_TASK_DURATION_MINUTES',
            'type' => 'int',
            'function' => array('name' => 'getTaskDurationMinutesOptions', 'returns' => 'html',
                'include' => 'modules/OnboardingOffboardingElements/OnboardingOffboardingElementsHelper.php'),
            'len' => '2',
            'group' => 'task_duration_hours',
            'importable' => 'required',
            'comment' => '',
            'reportable' => true,
            'audited' => true,
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
        ),
        'days_from_start' => array(
            'required' => true,
            'name' => 'days_from_start',
            'vname' => 'LBL_DAYS_FROM_START',
            'type' => 'int',
            'massupdate' => 0,
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
            'len' => '255',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'disable_num_format' => '',
            'min' => false,
            'max' => false,
        ),
        "users" => array(
            'name' => 'users',
            'type' => 'link',
            'relationship' => 'users_onboardingoffboardingelements',
            'source' => 'non-db',
            'module' => 'Users',
            'bean_name' => 'User',
            'vname' => 'LBL_USERS_ONBOARDINGOFFBOARDINGELEMENTS',
            'id_name' => 'user_id',
        ),
        "user_name" => array(
            'name' => 'user_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_USERS_NAME',
            'save' => true,
            'id_name' => 'user_id',
            'link' => 'users',
            'module' => 'Users',
            'table' => 'users',
            'rname' => 'name',
            'required' => true,
            'vt_dependency' => "equals(\$kind_of_element,'specific_user')",
        ),
        "user_id" => array(
            'name' => 'user_id',
            'relationship' => 'users_onboardingoffboardingelements',
            'type' => 'id',
            'vname' => 'LBL_USERS_ID',
        ),
        'onboardingtemplates' => array(
            'name' => 'onboardingtemplates',
            'type' => 'link',
            'relationship' => 'onboardingoffboardingelements_onboardingtemplates',
            'module' => 'OnboardingTemplates',
            'bean_name' => 'OnboardingTemplates',
            'source' => 'non-db',
            'vname' => 'LBL_ONBOARDINGTEMPLATES',
        ),
        'offboardingtemplates' => array(
            'name' => 'offboardingtemplates',
            'type' => 'link',
            'relationship' => 'onboardingoffboardingelements_offboardingtemplates',
            'module' => 'OffboardingTemplates',
            'bean_name' => 'OffboardingTemplates',
            'source' => 'non-db',
            'vname' => 'LBL_OFFBOARDINGTEMPLATES',
        ),
        'securitygroups_unit' => array(
            'name' => 'securitygroups_unit',
            'type' => 'link',
            'relationship' => 'securitygroups_unit_onboardingoffboardingelements',
            'source' => 'non-db',
            'module' => 'SecurityGroups',
            'bean_name' => 'SecurityGroup',
            'vname' => 'LBL_SECURITYGROUP_UNITS',
            'id_name' => 'securitygroup_unit_id',
        ),
        'securitygroup_unit_name' => array(
            'name' => 'securitygroup_unit_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_SECURITYGROUP_UNIT_NAME',
            'save' => true,
            'id_name' => 'securitygroup_unit_id',
            'link' => 'securitygroups_unit',
            'table' => 'securitygroups',
            'module' => 'SecurityGroups',
            'rname' => 'name',
            'required' => true,
            'vt_dependency' => "equals(\$kind_of_element,'organizational_unit_manager')",
        ),
        'securitygroup_unit_id' => array(
            'name' => 'securitygroup_unit_id',
            'type' => 'id',
            'relationship' => 'securitygroups_unit_onboardingoffboardingelements',
            'reportable' => false,
            'rname' => 'id',
            'isnull' => 'true',
            'dbType' => 'id',
            'vname' => 'LBL_SECURITYGROUP_UNIT_ID',
        ),
        "trainings" => array(
            'name' => 'trainings',
            'type' => 'link',
            'relationship' => 'onboardingoffboardingelements_trainings',
            'source' => 'non-db',
            'module' => 'Trainings',
            'bean_name' => 'Trainings',
            'vname' => 'LBL_RELATIONSHIP_TRAININGS_NAME',
        ),
    ),
    'relationships' => array(
        "users_onboardingoffboardingelements" => array(
            'lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'OnboardingOffboardingElements',
            'rhs_table' => 'onboardingoffboardingelements',
            'rhs_key' => 'user_id',
            'relationship_type' => 'one-to-many',
        ),
        "securitygroups_unit_onboardingoffboardingelements" => array(
            'lhs_module' => 'SecurityGroups',
            'lhs_table' => 'securitygroups',
            'lhs_key' => 'id',
            'rhs_module' => 'OnboardingOffboardingElements',
            'rhs_table' => 'onboardingoffboardingelements',
            'rhs_key' => 'securitygroup_unit_id',
            'relationship_type' => 'one-to-many',
        ),
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('OnboardingOffboardingElements',
    'OnboardingOffboardingElements',
    array(
        'basic',
        'assignable',
        'security_groups',
    )
);
