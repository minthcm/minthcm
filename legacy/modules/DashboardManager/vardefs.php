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

$dictionary['DashboardManager'] = array(
    'table' => 'dashboardmanager',
    'audited' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'encoded_pages' => array(
            'required' => false,
            'name' => 'encoded_pages',
            'vname' => 'LBL_ENCODED_PAGES',
            'type' => 'text',
            'massupdate' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 'false',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => false,
            'unified_search' => false,
            'calculated' => false,
            'size' => '20',
            'studio' => 'visible',
            'rows' => '4',
            'cols' => '20',
        ),
        'encoded_dashlets' => array(
            'required' => false,
            'name' => 'encoded_dashlets',
            'vname' => 'LBL_ENCODED_DASHLETS',
            'type' => 'text',
            'dbType' => 'mediumtext',
            'massupdate' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'calculated' => false,
            'size' => '20',
            'studio' => 'visible',
            'rows' => '4',
            'cols' => '20',
        ),
        'is_loaded' => array(
            'type' => 'bool',
            'name' => 'is_loaded',
            'vname' => 'LBL_IS_LOADED',
            'massupdate' => false,
            'audited' => true,
            'importable' => true,
            'default' => '0',
            'no_default' => false,
        ),
        'business_role' => array(
            'name' => 'business_role',
            'vname' => 'LBL_BUSINESS_ROLE',
            'label' => 'LBL_BUSINESS_ROLE',
            'type' => 'enum',
            'importable' => true,
            'reportable' => true,
            'audited' => true,
            'options' => 'business_role_list',
            'default' => '',
            'massupdate' => true,
            'required' => false,
            'vt_validation' => 'callCustomApi(\'DashboardManager\',\'validateUniqueRole\',{id:$id, business_role:$business_role},true,\'LBL_ROLE_EXISTS\')',
        ),
        "dashboardbackups" => array(
            'name' => 'dashboardbackups',
            'type' => 'link',
            'relationship' => 'dashboardbackups_dashboardmanager',
            'source' => 'non-db',
            'module' => 'DashboardBackups',
            'bean_name' => 'DashboardBackups',
            'vname' => 'LBL_DASHBOARDBACKUPS',
        ),
        "dashboardhistory" => array(
            'name' => 'dashboardhistory',
            'type' => 'link',
            'relationship' => 'dashboardhistory_dashboardmanager',
            'source' => 'non-db',
            'module' => 'DashboardHistory',
            'bean_name' => 'DashboardHistory',
            'vname' => 'LBL_DASHBOARDHISTORY',
        ),
        'users_locked_dashboards' => array(
            'name' => 'users_locked_dashboards',
            'type' => 'link',
            'relationship' => 'users_locked_dashboards',
            'source' => 'non-db',
            'module' => 'Users',
            'bean_name' => 'User',
            'vname' => 'LBL_USERS_LOCKED_DASHBOARDS',
        ),
        'users_forced_tabs_dashboards' => array(
            'name' => 'users_forced_tabs_dashboards',
            'type' => 'link',
            'relationship' => 'users_forced_tabs_dashboards',
            'source' => 'non-db',
            'module' => 'Users',
            'bean_name' => 'User',
            'vname' => 'LBL_USERS_FORCED_TABS_DASHBOARDS',
        ),
        'users_one_time_default_dashboards' => array(
            'name' => 'users_one_time_default_dashboards',
            'type' => 'link',
            'relationship' => 'users_one_time_default_dashboards',
            'source' => 'non-db',
            'module' => 'Users',
            'bean_name' => 'User',
            'vname' => 'LBL_USERS_ONE_TIME_DEFAULT_DASHBOARDS',
        ),
    ),
    'relationships' => array(
        'users_forced_tabs_dashboards' => array(
            'lhs_module' => 'DashboardManager',
            'lhs_table' => 'dashboardmanager',
            'lhs_key' => 'id',
            'rhs_module' => 'Users',
            'rhs_table' => 'users',
            'rhs_key' => 'forced_tabs_dashboard_id',
            'relationship_type' => 'one-to-many',
        ),
        'users_locked_dashboards' => array(
            'lhs_module' => 'DashboardManager',
            'lhs_table' => 'dashboardmanager',
            'lhs_key' => 'id',
            'rhs_module' => 'Users',
            'rhs_table' => 'users',
            'rhs_key' => 'locked_dashboard_id',
            'relationship_type' => 'one-to-many',
        ),
        'users_one_time_default_dashboards' => array(
            'lhs_module' => 'DashboardManager',
            'lhs_table' => 'dashboardmanager',
            'lhs_key' => 'id',
            'rhs_module' => 'Users',
            'rhs_table' => 'users',
            'rhs_key' => 'one_time_default_dashboard_id',
            'relationship_type' => 'one-to-many',
        ),
        'dashboardbackups_dashboardmanager' => array(
            'lhs_module' => 'DashboardManager',
            'lhs_table' => 'dashboardmanager',
            'lhs_key' => 'id',
            'rhs_module' => 'DashboardBackups',
            'rhs_table' => 'dashboardbackups',
            'rhs_key' => 'dashboardmanager_id',
            'relationship_type' => 'one-to-many',
        ),
        'dashboardhistory_dashboardmanager' => array(
            'lhs_module' => 'DashboardManager',
            'lhs_table' => 'dashboardmanager',
            'lhs_key' => 'id',
            'rhs_module' => 'DashboardHistory',
            'rhs_table' => 'dashboardhistory',
            'rhs_key' => 'dashboardmanager_id',
            'relationship_type' => 'one-to-many',
        ),
    ),
    'optimistic_locking' => true,
    'unified_search' => false,
);
if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('DashboardManager', 'DashboardManager', array('basic', 'assignable'));

$dictionary['DashboardManager']['fields']['name']['audited'] = true;
$dictionary['DashboardManager']['fields']['assigned_user_id']['audited'] = true;
