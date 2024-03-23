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
$dictionary['Onboardings'] = array(
   'table' => 'onboardings',
   'audited' => true,
   'inline_edit' => true,
   'duplicate_merge' => true,
   'fields' => array(
      'status' =>
      array(
         'required' => false,
         'name' => 'status',
         'vname' => 'LBL_STATUS',
         'type' => 'enum',
         'massupdate' => '1',
         'default' => 'in_progress',
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
         'options' => 'onboarding_status_list',
         'studio' => 'visible',
         'dependency' => false,
      ),
      'date_start' =>
      array(
         'required' => false,
         'name' => 'date_start',
         'vname' => 'LBL_DATE_START',
         'type' => 'datetimecombo',
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
      'onboardingtemplate' =>
      array(
         'name' => 'onboardingtemplate',
         'type' => 'link',
         'relationship' => 'onboardings_onboardingtemplates',
         'source' => 'non-db',
         'module' => 'OnboardingTemplates',
         'bean_name' => 'OnboardingTemplates',
         'vname' => 'LBL_ONBOARDINGS_ONBOARDINGTEMPLATES_TITLE',
         'id_name' => 'onboardingtemplate_id',
      ),
      'onboardingtemplate_name' =>
      array(
         'name' => 'onboardingtemplate_name',
         'type' => 'relate',
         'source' => 'non-db',
         'vname' => 'LBL_ONBOARDINGTEMPLATE_NAME',
         'save' => true,
         'id_name' => 'onboardingtemplate_id',
         'link' => 'onboardingtemplate',
         'table' => 'onboardingtemplates',
         'module' => 'OnboardingTemplates',
         'rname' => 'name',
         'required' => false,
      ),
      'onboardingtemplate_id' =>
      array(
         'name' => 'onboardingtemplate_id',
         'type' => 'link',
         'relationship' => 'onboardings_onboardingtemplates',
         'reportable' => false,
         'vname' => 'LBL_ONBOARDINGTEMPLATE_ID', 
         'dbType' => 'id',
      ),
      'trainings' => array(
         'name' => 'trainings',
         'type' => 'link',
         'relationship' => 'onboardings_trainings',
         'module' => 'Trainings',
         'bean_name' => 'Trainings',
         'source' => 'non-db',
         'vname' => 'LBL_RELATIONSHIP_TRAININGS_NAME',
      ),
      'tasks' => array(
         'name' => 'tasks',
         'type' => 'link',
         'relationship' => 'onboardings_tasks',
         'module' => 'Tasks',
         'bean_name' => 'Task',
         'source' => 'non-db',
         'vname' => 'LBL_RELATIONSHIP_TRAININGS_NAME',
      ),
   ),
   'relationships' => array(
      'onboardings_onboardingtemplates' =>
      array(
         'lhs_module' => 'OnboardingTemplates',
         'lhs_table' => 'onboardingtemplates',
         'lhs_key' => 'id',
         'rhs_module' => 'Onboardings',
         'rhs_table' => 'onboardings',
         'rhs_key' => 'onboardingtemplate_id',
         'relationship_type' => 'one-to-many',
      ),
      "onboardings_trainings" => array(
         'lhs_module' => 'Onboardings',
         'lhs_table' => 'onboardings',
         'lhs_key' => 'id',
         'rhs_module' => 'Trainings',
         'rhs_table' => 'trainings',
         'rhs_key' => 'parent_id',
         'relationship_type' => 'one-to-many',
         'relationship_role_column' => 'parent_type',
         'relationship_role_column_value' => 'Onboardings'
      ),
      "onboardings_tasks" => array(
         'lhs_module' => 'Onboardings',
         'lhs_table' => 'onboardings',
         'lhs_key' => 'id',
         'rhs_module' => 'Tasks',
         'rhs_table' => 'tasks',
         'rhs_key' => 'parent_id',
         'relationship_type' => 'one-to-many',
         'relationship_role_column' => 'parent_type',
         'relationship_role_column_value' => 'Onboardings'
      ),
   ),
   'optimistic_locking' => true,
   'unified_search' => true,
);
if ( !class_exists('VardefManager') ) {
   require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('Onboardings', 'Onboardings',
        array(
           'basic',
           'assignable',
           'employee_related',
           'security_groups'
        )
);
