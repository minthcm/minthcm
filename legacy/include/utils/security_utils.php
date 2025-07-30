<?php
if (!defined('sugarEntry') || !sugarEntry) {
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


/*
 * func: query_module_access
 * param: $moduleName
 *
 * returns 1 if user has access to a module, else returns 0
 *
 */

$modules_exempt_from_availability_check['Activities']='Activities';
$modules_exempt_from_availability_check['History']='History';
$modules_exempt_from_availability_check['Calls']='Calls';
$modules_exempt_from_availability_check['Meetings']='Meetings';
$modules_exempt_from_availability_check['Tasks']='Tasks';
//$modules_exempt_from_availability_check['Notes']='Notes';

$modules_exempt_from_availability_check['CampaignLog']='CampaignLog';
$modules_exempt_from_availability_check['CampaignTrackers']='CampaignTrackers';
$modules_exempt_from_availability_check['Prospects']='Prospects';
$modules_exempt_from_availability_check['ProspectLists']='ProspectLists';
$modules_exempt_from_availability_check['EmailMarketing']='EmailMarketing';
$modules_exempt_from_availability_check['EmailMan']='EmailMan';
$modules_exempt_from_availability_check['ProjectTask']='ProjectTask';
$modules_exempt_from_availability_check['Users']='Users';
$modules_exempt_from_availability_check['Teams']='Teams';
$modules_exempt_from_availability_check['SchedulersJobs']='SchedulersJobs';
$modules_exempt_from_availability_check['DocumentRevisions']='DocumentRevisions';
function query_module_access_list(&$user)
{
    require_once('modules/MySettings/TabController.php');
    $controller = new TabController();
    
    if($user->isAdmin()) {
        $tabArray = $controller->get_admin_tabs($user);
    } else {
        $tabArray = $controller->get_tabs($user);
    }

    return $tabArray[0];
}

function query_user_has_roles($user_id)
{
    $role = BeanFactory::newBean('Roles');

    return $role->check_user_role_count($user_id);
}

function get_user_allowed_modules($user_id)
{
    $role = BeanFactory::newBean('Roles');

    $allowed = $role->query_user_allowed_modules($user_id);
    return $allowed;
}

function get_user_disallowed_modules($user_id, &$allowed)
{
    $role = BeanFactory::newBean('Roles');
    $disallowed = $role->query_user_disallowed_modules($user_id, $allowed);
    return $disallowed;
}
// grabs client ip address and returns its value
function query_client_ip()
{
    if (!empty($GLOBALS['sugar_config']['ip_variable']) && !empty($_SERVER[$GLOBALS['sugar_config']['ip_variable']])) {
        return $_SERVER[$GLOBALS['sugar_config']['ip_variable']];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
        return $_SERVER['HTTP_X_FORWARDED'];
    } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
        return $_SERVER['HTTP_FORWARDED'];
    } elseif (isset($_SERVER['HTTP_FROM'])) {
        return $_SERVER['HTTP_FROM'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        return $_SERVER['REMOTE_ADDR'];
    } else {
        $GLOBALS['log']->warn('query_client_ip(): Unable to detect the IP address of the client.');
        return null;
    }
}

// sets value to key value
function get_val_array($arr)
{
    $new = array();
    if (!empty($arr)) {
        foreach ($arr as $key=>$val) {
            $new[$key] = $key;
        }
    }
    return $new;
}
