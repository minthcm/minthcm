<?php
if (! defined('sugarEntry') || ! sugarEntry) {
    die('Not A Valid Entry Point');
}
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

global $currentModule;
global $theme;
global $focus;
global $action;

global $mod_strings;
global $app_strings;
global $locale;

// focus_list is the means of passing data to a SubPanelView.
global $focus_users_list;
global $focus_contacts_list;

$button = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";
$button .= "<input type='hidden' name='module' value='Contacts'>\n";
$button .= "<input type='hidden' name='return_module' value='" . $currentModule . "'>\n";
$button .= "<input type='hidden' name='return_action' value='" . $action . "'>\n";
$button .= "<input type='hidden' name='return_id' value='" . $focus->id . "'>\n";
$button .= "<input type='hidden' name='action'>\n";
$button .= "<tr><td>&nbsp;</td>";

$button .= "<td><input title='" . $app_strings['LBL_SELECT_CONTACT_BUTTON_TITLE'] . "'  type='button' class='button' value='" . $app_strings['LBL_SELECT_CONTACT_BUTTON_LABEL'] . "' name='button' LANGUAGE=javascript onclick='window.open(\"index.php?module=Contacts&action=Popup&html=Popup_picker&form=DetailView&form_submit=true\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\");'></td>\n";

$button .= "<td><input title='" . $app_strings['LBL_SELECT_USER_BUTTON_TITLE'] . "'  type='button' class='button' value='" . $app_strings['LBL_SELECT_USER_BUTTON_LABEL'] . "' name='button' LANGUAGE=javascript onclick='window.open(\"index.php?module=Users&action=Popup&html=Popup_picker&form=DetailView&form_submit=true\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\");'></td>\n";
$button .= "</tr></form></table>\n";

// Stick the form header out there.
echo get_form_header($mod_strings['LBL_INVITEES'], $button, false);
$xtpl = new XTemplate('modules/Calls/SubPanelViewInvitees.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=$focus->id");
$xtpl->assign("CALL_ID", $focus->id);

$oddRow = true;
foreach ($focus_users_list as $user) {
    $user_fields = [
        'USER_NAME' => $user->user_name,
        'FULL_NAME' => $locale->getLocaleFormattedName($user->first_name, $user->last_name),
        'ID' => $user->id,
        'EMAIL' => $user->email1,
        'PHONE_WORK' => $user->phone_work,
    ];

    $xtpl->assign("USER", $user_fields);

    if ($oddRow) {
        //todo move to themes
        $xtpl->assign("ROW_COLOR", 'oddListRow');
    } else {
        //todo move to themes
        $xtpl->assign("ROW_COLOR", 'evenListRow');
    }
    $oddRow = ! $oddRow;

    $xtpl->parse("users.row");
    // Put the rows in.
}

$xtpl->parse("users");
$xtpl->out("users");

$oddRow = true;
foreach ($focus_contacts_list as $contact) {
    $contact_fields = [
        'FIRST_NAME' => $contact->first_name,
        'LAST_NAME' => $contact->last_name,
        'ID' => $contact->id,
        'EMAIL' => $contact->email1,
        'PHONE_WORK' => $contact->phone_work,
    ];

    $xtpl->assign("CONTACT", $contact_fields);

    if ($oddRow) {
        //todo move to themes
        $xtpl->assign("ROW_COLOR", 'oddListRow');
    } else {
        //todo move to themes
        $xtpl->assign("ROW_COLOR", 'evenListRow');
    }
    $oddRow = ! $oddRow;

    $xtpl->parse("contacts.row");
    // Put the rows in.
}

$xtpl->parse("contacts");
$xtpl->out("contacts");
