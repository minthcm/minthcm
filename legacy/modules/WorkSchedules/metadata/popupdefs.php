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
$subqueries = [];
if (isset($_REQUEST['status_perm_advanced']) && !empty($_REQUEST['status_perm_advanced'])) {
    $status = json_decode(str_replace('&quot;', '"', $_REQUEST['status_perm_advanced']));
    $_REQUEST['status_advanced'] = $status;
    $subqueries[] = "workschedules.status IN ('" . implode("','", $status) . "')";
}

if (isset($_REQUEST['assigned_to_perm_advanced']) && !empty($_REQUEST['assigned_to_perm_advanced'])) {
    $_REQUEST['assigned_user_id_advanced'] = $_REQUEST['assigned_to_perm_advanced'];
    $subqueries[] = "workschedules.assigned_user_id = '{$_REQUEST['assigned_user_id_advanced']}'";
} else {
    global $current_user;
    $subqueries[] = "workschedules.assigned_user_id = '{$current_user->id}'";
}

if (isset($_REQUEST['type_perm_advanced']) && !empty($_REQUEST['type_perm_advanced'])) {
    $status = json_decode(str_replace('&quot;', '"', $_REQUEST['type_perm_advanced']));
    $_REQUEST['type_advanced'] = $status;
    $subqueries[] = "workschedules.type IN ('" . implode("','", $status) . "')";
}

$whereStatement = implode(" AND ", $subqueries);
$popupMeta = array(
    'moduleMain' => 'workschedules',
    'varName' => 'workschedules',
    'orderBy' => 'workschedules.name',
    'whereStatement' => $whereStatement,
    'whereClauses' => array(
        'schedule_date' => 'workschedules.schedule_date',
        'type' => 'workschedules.type',
        'spent_time' => 'workschedules.spent_time',
        'status' => 'workschedules.status',
    ),
    'searchInputs' => array(
        'status',
        'schedule_date',
        'type',
        'spent_time',
    ),
    'searchdefs' => array(
        'type' => array(
            'type' => 'enum',
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
            'width' => '10%',
            'name' => 'type',
        ),
        'status' => array(
            'type' => 'enum',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
            'width' => '10%',
            'name' => 'status',
        ),
        'supervisor_acceptance' => array(
            'type' => 'enum',
            'studio' => 'visible',
            'label' => 'LBL_SUPERVISOR_ACCEPTANCE',
            'width' => '10%',
            'name' => 'supervisor_acceptance',
        ),
        'ASSIGNED_TO_PERM' => array(
            'type' => 'varchar',
            'studio' => array('editview' => 'false'),
            'label' => '',
            'default' => true,
            'name' => 'assigned_to_perm',
            'displayParams' => array('hidden' => true),
        ),
        'TYPE_PERM' => array(
            'type' => 'varchar',
            'studio' => array('editview' => 'false'),
            'label' => '',
            'default' => true,
            'name' => 'type_perm',
            'displayParams' => array('hidden' => true),
        ),
        'STATUS_PERM' => array(
            'type' => 'varchar',
            'studio' => array('editview' => 'false'),
            'label' => '',
            'default' => true,
            'name' => 'status_perm',
            'displayParams' => array('hidden' => true),
        ),
    ),
    'listviewdefs' => array(
        'NAME' => array(
            'type' => 'name',
            'link' => true,
            'label' => 'LBL_NAME',
            'width' => '10%',
            'default' => true,
        ),
        'STATUS' => array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
            'width' => '10%',
        ),
        'ASSIGNED_USER_NAME' => array(
            'link' => 'assigned_user_link',
            'type' => 'relate',
            'label' => 'LBL_ASSIGNED_TO_NAME',
            'width' => '10%',
            'default' => true,
            'name' => 'assigned_user_name',
        ),
    ),
);
//echo '<script type="text/javascript">$(document).ready(function(){$("#status_advanced option[value=\'closed\']").remove();});</script>';
