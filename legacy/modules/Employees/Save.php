<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
/* * *******************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.

 * SuiteCRM is an extension to SugarCRM Community Edition developed by Salesagility Ltd.
 * Copyright (C) 2011 - 2014 Salesagility Ltd.
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
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
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
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 * ****************************************************************************** */

/* * *******************************************************************************

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * ****************************************************************************** */

require_once 'modules/MySettings/TabController.php';
require_once 'include/SugarFields/SugarFieldHandler.php';
require_once 'modules/Employees/Repository/EmployeesRepository.php';

$module_name = 'Employees';

$tabs_def = urldecode(isset($_REQUEST['display_tabs_def']) ? $_REQUEST['display_tabs_def'] : '');
$DISPLAY_ARR = array();
parse_str($tabs_def, $DISPLAY_ARR);

//there was an issue where a non-admin user could use a proxy tool to intercept the save on their own Employee
//record and swap out their record_id with the admin employee_id which would cause the email address
//of the non-admin user to be associated with the admin user thereby allowing the non-admin to reset the password
//of the admin user.
if (
    isset($_POST['record']) &&
    !is_admin($GLOBALS['current_user']) &&
    !$GLOBALS['current_user']->isAdminForModule('Employees') &&
    ($_POST['record'] != $GLOBALS['current_user']->id) &&
    !ACLAction::userHasAccess($GLOBALS['current_user']->id, $this->module, 'edit')
) {
    sugar_die("Unauthorized access to administration.");
} elseif (
    !isset($_POST['record']) &&
    !is_admin($GLOBALS['current_user']) &&
    !$GLOBALS['current_user']->isAdminForModule('Employees')
) {
    sugar_die("Unauthorized access to user administration.");
}

$focus = new Employee();

$focus->retrieve(!empty($_POST['record']) ? $_POST['record'] : $_POST['id']);

//rrs bug: 30035 - I am not sure how this ever worked b/c old_reports_to_id was not populated.
$old_reports_to_id = $focus->reports_to_id;


$retrieved_focus = $focus;
populateFromRow($focus, $_POST);

// handles duplicate search for updates
if (!empty($focus->fetched_row) && !empty($_POST['id']) && $retrieved_focus->id === $_POST['id']) {
    $focus->new_with_id = false;
}

$focus->email1 = $_REQUEST[$_REQUEST["Users0emailAddressPrimaryFlag"]];

if (empty($_POST['dup_checked'])) {

    $duplicates = EmployeesRepository::getDuplicatedCandidatesEmployeesRecordsIds($focus);
    if (!empty($duplicates)) {
        $location = "module={$module_name}&action=showduplicates";
        
        $get = '';
        if (isset($_POST['inbound_email_id']) && !empty($_POST['inbound_email_id'])) {
            $get .= '&inbound_email_id=' . $_POST['inbound_email_id'];
        }

        if (isset($_POST['relate_to']) && !empty($_POST['relate_to'])) {
            $get .= "&{$module_name}relate_to=" . $_POST['relate_to'];
        }
        if (isset($_POST['relate_id']) && !empty($_POST['relate_id'])) {
            $get .= "&{$module_name}relate_id='" . $_POST['relate_id'];
        }

        foreach ($focus->column_fields as $field) {
            if (!empty($focus->$field) && !is_object($focus->$field)) {
                $get .= "&{$module_name}$field=" . urlencode($focus->$field);
            }
        }

        foreach ($focus->additional_column_fields as $field) {
            if (!empty($focus->$field)) {
                $get .= "&{$module_name}$field=" . urlencode($focus->$field);
            }
        }

        if ($focus->hasCustomFields()) {
            foreach ($focus->field_defs as $name => $field) {
                if (!empty($field['source']) && 'custom_fields' == $field['source']) {
                    $get .= "&{$module_name}$name=" . urlencode($focus->$name);
                }
            }
        }

        $emailAddress = new SugarEmailAddress();
        $get .= $emailAddress->getFormBaseURL($focus);

        foreach ($duplicates as $index => $duplicated_id) {
            $get .= "&duplicate[$index]=" . $duplicated_id;
        }

        $urlData = ['return_module' => $module_name, 'return_action' => ''];
        foreach (['return_module', 'return_action', 'return_id', 'popup', 'create', 'start'] as $var) {
            if (!empty($_POST[$var])) {
                $urlData[$var] = $_POST[$var];
            }
        }
        $get .= "&" . http_build_query($urlData);
        $_SESSION['SHOW_DUPLICATES'] = $get;

        if (!empty($_POST['is_ajax_call']) && '1' == $_POST['is_ajax_call']) {
            ob_clean();
            $json = getJSONobj();
            echo $json->encode(array('status' => 'dupe', 'get' => $location));
        } else if (!empty($_REQUEST['ajax_load'])) {
            echo "<script>SUGAR.ajaxUI.loadContent('index.php?{$location}');</script>";
        } else {
            if (!empty($_POST['to_pdf'])) {
                $location .= '&to_pdf=' . urlencode($_POST['to_pdf']);
            }

            header("Location: index.php?{$location}");
        }
        return null;
    }
}
$focus->email1 = $_REQUEST['email1'];
$focus->save();
$return_id = $focus->id;

if (isset($_POST['return_module']) && $_POST['return_module'] != "") {
    $return_module = $_POST['return_module'];
} else {
    $return_module = "Employees";
}
if (isset($_POST['return_action']) && $_POST['return_action'] != "") {
    $return_action = $_POST['return_action'];
} else {
    $return_action = "DetailView";
}
if (isset($_POST['return_id']) && $_POST['return_id'] != "") {
    $return_id = $_POST['return_id'];
}

$GLOBALS['log']->debug("Saved record with id of " . $return_id);

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");

function populateFromRow(&$focus, $row)
{

    //only employee specific field values need to be copied.
    $e_fields = array(
        'first_name',
        'last_name',
        'reports_to_id',
        'description',
        'phone_home',
        'position_id',
        'phone_mobile',
        'phone_work',
        'phone_other',
        'phone_fax',
        'address_street',
        'address_city',
        'address_state',
        'address_country',
        'address_country',
        'address_postalcode',
        'messenger_id',
        'messenger_type',
        'email1',
        'securitygroup_id',
    );

    if (is_admin($GLOBALS['current_user'])) {
        $e_fields = array_merge($e_fields, array('employee_status'));
    }
    if (file_exists('custom/modules/Employees/whitelist_fields.php')) {
        require_once 'custom/modules/Employees/whitelist_fields.php';
        $e_fields = array_merge($e_fields, $whitelist_fields);
    }
    if (isset($row['Users0emailAddress0'])) {
        $row['email1'] = $row['Users0emailAddress0'];
    }
    // Also add custom fields
    $sfh = new SugarFieldHandler();
    foreach ($focus->field_defs as $fieldName => $field) {
        if (
            (isset($field['source']) && $field['source'] == 'custom_fields')
            || $fieldName == 'photo') {
            $type = !empty($field['custom_type']) ? $field['custom_type'] : $field['type'];
            $sf = $sfh->getSugarField($type);
            if ($sf != null) {
                $sf->save($focus, $_POST, $fieldName, $field, '');
            } else {
                $GLOBALS['log']->fatal("Field '$fieldName' does not have a SugarField handler");
            }
        }
    }
    $nullvalue = '';
    foreach ($e_fields as $field) {
        $rfield = $field; // fetch returns it in lowercase only
        if (isset($row[$rfield])) {
            $focus->$field = $row[$rfield];
        }
    }
}
