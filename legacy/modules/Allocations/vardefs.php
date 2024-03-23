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
$dictionary['Allocations'] = array(
    'table' => 'allocations',
    'audited' => true,
    'inline_edit' => false,
    'duplicate_merge' => true,
    'fields' => array(
        'mode' =>
        array(
            'required' => false,
            'name' => 'mode',
            'vname' => 'LBL_MODE',
            'type' => 'enum',
            'massupdate' => '0',
            'default' => '',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '1',
            'audited' => true,
            'inline_edit' => '',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 100,
            'size' => '20',
            'options' => 'allocation_mode_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'date_from' =>
        array(
            'required' => true,
            'name' => 'date_from',
            'vname' => 'LBL_DATE_FROM',
            'type' => 'date',
            'massupdate' => '0',
            'default' => '',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'audited' => true,
            'validation' => array('type' => 'isbefore', 'compareto' => 'date_to'),
        ),
        'date_to' =>
        array(
            'required' => false,
            'name' => 'date_to',
            'vname' => 'LBL_DATE_TO',
            'type' => 'date',
            'massupdate' => '0',
            'default' => '',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'audited' => true,
        ),
        "workplaces_allocations" => array(
            'name' => 'workplaces_allocations',
            'type' => 'link',
            'relationship' => 'workplaces_allocations',
            'source' => 'non-db',
            'module' => 'Workplaces',
            'bean_name' => 'Workplaces',
            'vname' => 'LBL_RELATIONSHIP_WORKPLACES',
            'id_name' => 'workplace_id',
         ),
         "workplace_name" => array(                       
            'required' => true,
            'name' => 'workplace_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_RELATIONSHIP_WORKPLACES',
            'id_name' => 'workplace_id',
            'link' => 'workplaces_allocations',
            'module' => 'Workplaces',
            'table' => 'workplaces',
            'rname' => 'name',
            'vt_validation' => array(
                "AEM(callCustomApi('Allocations','checkWorkplaceStatus',\$workplace_id,\$mode),'LBL_ERR_WORKPLACE_STATUS')",
                "AEM(callCustomApi('Allocations','checkWorkplacePeriods',\$id,\$workplace_id,\$mode,\$date_from,\$date_to),'LBL_ERR_WORKPLACE_PERIODS')",
            ),
         ),
         "workplace_id" => array(
            'name' => 'workplace_id',
            'relationship' => 'workplaces_allocations',
            'type' => 'id',
            'vname' => 'LBL_RELATIONSHIP_WORKPLACES_ID',
         ),
         "allocations_employees" => array(
            'name' => 'allocations_employees',                          
            'type' => 'link',
            'relationship' => 'allocations_employees',                 
            'source' => 'non-db',
            'module' => 'Employees',                                   
            'bean_name' => 'Employee',                                     
            'vname' => 'LBL_LINKED_USERS_TITLE',                         
         ),
    ),
    'relationships' => array(
        "workplaces_allocations" => array(
            'lhs_module' => 'Workplaces',
            'lhs_table' => 'workplaces',
            'lhs_key' => 'id',
            'rhs_module' => 'Allocations',
            'rhs_table' => 'allocations',
            'rhs_key' => 'workplace_id',
            'relationship_type' => 'one-to-many',
         ),
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once('include/SugarObjects/VardefManager.php');
}

VardefManager::createVardef('Allocations', 'Allocations',
    array(
    'basic',
    'assignable',
    'security_groups'
));

$dictionary['Allocations']['fields']['assigned_user_name']['required'] = true;
$dictionary['Allocations']['fields']['assigned_user_name']['audited'] = true;
$dictionary['Allocations']['fields']['assigned_user_id']['audited'] = false;
$dictionary['Allocations']['fields']['name']['audited'] = true;

