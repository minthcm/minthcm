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

$dictionary['DashboardBackups'] = array(
    'table' => 'dashboardbackups',
    'audited' => false,
    'inline_edit' => false,
    'duplicate_merge' => false,
    'fields' => array(
        'encoded_pages' => array(
            'required' => false,
            'name' => 'encoded_pages',
            'vname' => 'LBL_ENCODED_PAGES',
            'type' => 'text',
            'massupdate' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
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
        "dashboardmanager" => array(
            'name' => 'dashboardmanager',
            'type' => 'link',
            'relationship' => 'dashboardbackups_dashboardmanager',
            'source' => 'non-db',
            'module' => 'DashboardManager',
            'bean_name' => false,
            'vname' => 'LBL_DASHBOARDMANAGER',
        ),
        "dashboardmanager_name" => array(
            'id_name' => 'dashboardmanager_id',
            'name' => 'dashboardmanager_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_DASHBOARDMANAGER_NAME',
            'link' => 'dashboardmanager',
            'table' => 'dashboardmanager',
            'module' => 'DashboardManager',
            'rname' => 'name',
        ),
        "dashboardmanager_id" => array(
            'name' => 'dashboardmanager_id',
            'type' => 'id',
            'vname' => 'LBL_DASHBOARDMANAGER_ID',
            'link' => 'dashboardmanager',
            'table' => 'dashboardmanager',
            'module' => 'DashboardManager',
            'rname' => 'id',
            'reportable' => false,
            'massupdate' => false,
            'duplicate_merge' => 'disabled',
            'hideacl' => true,
        ),
        "dashboardhistory" => array(
            'name' => 'dashboardhistory',
            'type' => 'link',
            'relationship' => 'dashboardbackups_dashboardhistory',
            'source' => 'non-db',
            'module' => 'DashboardHistory',
            'bean_name' => 'ev_DashboardHistory',
            'vname' => 'LBL_DASHBOARDHISTORY',
        ),
        "dashboardhistory_name" => array(
            'id_name' => 'dashboardhistory_id',
            'name' => 'dashboardhistory_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_DASHBOARDHISTORY_NAME',
            'link' => 'dashboardhistory',
            'table' => 'dashboardhistory',
            'module' => 'DashboardHistory',
            'rname' => 'name',
        ),
        "dashboardhistory_id" => array(
            'name' => 'dashboardhistory_id',
            'type' => 'id',
            'vname' => 'LBL_DASHBOARDHISTORY_ID',
            'link' => 'dashboardhistory',
            'table' => 'dashboardhistory',
            'module' => 'DashboardHistory',
            'rname' => 'id',
            'reportable' => false,
            'massupdate' => false,
            'duplicate_merge' => 'disabled',
            'hideacl' => true,
        ),
    ),
    'relationships' => array(
    ),
    'optimistic_locking' => true,
    'unified_search' => false,
);
if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('DashboardBackups', 'DashboardBackups', array('basic', 'assignable'));
