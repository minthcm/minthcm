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


function loadParentView($type)
{
    if (file_exists('custom/include/MVC/View/views/view.'.$type.'.php')) {
        require_once('custom/include/MVC/View/views/view.'.$type.'.php');
    } else {
        if (file_exists('include/MVC/View/views/view.'.$type.'.php')) {
            require_once('include/MVC/View/views/view.'.$type.'.php');
        }
    }
}


function getPrintLink()
{
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "ajaxui") {
        return "javascript:SUGAR.ajaxUI.print();";
    }
    $requestString = null;
    if (isset($GLOBALS['request_string'])) {
        $requestString = $GLOBALS['request_string'];
    } else {
        LoggerManager::getLogger()->warn('Undefined index: request_string');
    }
    return "javascript:void window.open('index.php?{$requestString}',"
         . "'printwin','menubar=1,status=0,resizable=1,scrollbars=1,toolbar=0,location=1')";
}


function ajaxBannedModules()
{
    $bannedModules = array(
        'Calendar',
        'Emails',
        'Campaigns',
        'Documents',
        'DocumentRevisions',
        'Project',
        'ProjectTask',
        'EmailMarketing',
        'CampaignLog',
        'CampaignTrackers',
        'Releases',
        'Groups',
        'EmailMan',
        "Administration",
        "ModuleBuilder",
        'Schedulers',
        'SchedulersJobs',
        'DynamicFields',
        'EditCustomFields',
        'EmailTemplates',
        'Users',
        'Currencies',
        'Trackers',
        'Connectors',
        'Import_1',
        'Import_2',
        'Versions',
        'vCals',
        'CustomFields',
        'Roles',
        'Audit',
        'InboundEmail',
        'SavedSearch',
        'UserPreferences',
        'MergeRecords',
        'EmailAddresses',
        'Relationships',
        'Employees',
        'Import',
        'OAuthKeys',
        'Surveys',
    );

    if (!empty($GLOBALS['sugar_config']['addAjaxBannedModules'])) {
        $bannedModules = array_merge($bannedModules, $GLOBALS['sugar_config']['addAjaxBannedModules']);
    }
    if (!empty($GLOBALS['sugar_config']['overrideAjaxBannedModules'])) {
        $bannedModules = $GLOBALS['sugar_config']['overrideAjaxBannedModules'];
    }

    return $bannedModules;
}

// mint start
function ajaxBannedActions()
{
    $bannedActions = [
    ];
    if (!empty($GLOBALS['sugar_config']['addAjaxBannedActions'])) {
        $bannedActions = array_merge($bannedActions, $GLOBALS['sugar_config']['addAjaxBannedActions']);
    }
    return $bannedActions;
}
// mint stop

function ajaxLink($url)
{
    global $sugar_config;
    $match = array();
    $javascriptMatch = array();

    preg_match('/module=([^&]*)/i', $url, $match);
    preg_match('/action=([^&]*)/i', $url, $actionMatch); // Mint
    preg_match('/^javascript/i', $url, $javascriptMatch);

    if (!empty($sugar_config['disableAjaxUI'])) {
        return $url;
    } else {
        if (isset($match[1]) && in_array($match[1], ajaxBannedModules())) {
            return $url;
        } else if (isset($actionMatch[1]) && in_array(strtolower($actionMatch[1]), ajaxBannedActions())) { // Mint
            return $url;
        }
        //Don't modify javascript calls.
        else {
            if (isset($javascriptMatch[0])) {
                return $url;
            } else {
                return "?action=ajaxui#ajaxUILoc=" . urlencode($url);
            }
        }
    }
}
