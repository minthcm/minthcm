<?php

/**
 * MintHCM #52883 #59127
 * We need to reload preferences, because they can be updated anytime
 * Second change, adds DMForced property sending to Smarty template
 */
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
/* * *******************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.

 * SuiteCRM is an extension to SugarCRM Community Edition developed by Salesagility Ltd.
 * Copyright (C) 2011 - 2016 Salesagility Ltd.
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

// MintHCM #94842 START
global $current_user, $sugar_version, $sugar_config, $beanFiles, $dashlet_initial_loading;
// MintHCM #94842 END

require_once 'include/MySugar/MySugar.php';

// build dashlet cache file if not found
if (!is_file($cachefile = sugar_cached('dashlets/dashlets.php'))) {
    require_once 'include/Dashlets/DashletCacheBuilder.php';

    $dc = new DashletCacheBuilder();
    $dc->buildCache();
}
require_once $cachefile;

require 'modules/Home/dashlets.php';

// MintHCM start #52883
if (is_object($current_user->_userPreferenceFocus)) {
    $current_user->_userPreferenceFocus->reloadPreferences('Home');
}
// MintHCM end #52883
$pages = $current_user->getPreference('pages', 'Home');
$dashlets = $current_user->getPreference('dashlets', 'Home');

$defaultHomepage = false;
// BEGIN fill in with default homepage and dashlet selections

$hasUserPreferences = (!isset($pages) || empty($pages) || !isset($dashlets) || empty($dashlets)) ? false : true;

if (!$hasUserPreferences) {
    $dashlets = array();

    //list of preferences to move over and to where
    $prefstomove = array(
    );

    //upgrading from pre-5.0 homepage
    $old_columns = $current_user->getPreference('columns', 'home');
    $old_dashlets = $current_user->getPreference('dashlets', 'home');

    if (isset($old_columns) && !empty($old_columns) && isset($old_dashlets) && !empty($old_dashlets)) {
        $columns = $old_columns;
        $dashlets = $old_dashlets;

        // resetting old columns and dashlets to have no preference and data
        $old_columns = array();
        $old_dashlets = array();
        $current_user->setPreference('columns', $old_columns, 0, 'home');
        $current_user->setPreference('dashlets', $old_dashlets, 0, 'home');
    } else {
        foreach ($defaultDashlets as $dashletName => $module) {
            // clint - fixes bug #20398
            // only display dashlets that are from visibile modules and that the user has permission to list
            $myDashlet = new MySugar($module);
            $displayDashlet = $myDashlet->checkDashletDisplay();
            if (isset($dashletsFiles[$dashletName]) && $displayDashlet) {
                $options = array();
                $prefsforthisdashlet = array_keys($prefstomove, $dashletName);
                foreach ($prefsforthisdashlet as $pref) {
                    $options[$pref] = $current_user->getPreference($pref);
                }
                $dashlets[create_guid()] = array('className' => $dashletName,
                    'module' => $module,
                    'forceColumn' => (int) ($module === 'Calendar'),
                    'fileLocation' => $dashletsFiles[$dashletName]['file'],
                    'options' => $options);
            }
        }

        $count = 0;
        $columns = array();
        $columns[0] = array();
        $columns[0]['width'] = '60%';
        $columns[0]['dashlets'] = array();
        $columns[1] = array();
        $columns[1]['width'] = '40%';
        $columns[1]['dashlets'] = array();

        foreach ($dashlets as $guid=>$dashlet) {
            if ($dashlet['forceColumn'] == 0) {
                array_push($columns[0]['dashlets'], $guid);
            } else {
                array_push($columns[1]['dashlets'], $guid);
            }
            $count++;
        }
    }

    $current_user->setPreference('dashlets', $dashlets, 0, 'Home');
}

// handles upgrading from versions that had the 'Dashboard' module; move those items over to the Home page
$pagesDashboard = $current_user->getPreference('pages', 'Dashboard');
$dashletsDashboard = $current_user->getPreference('dashlets', 'Dashboard');
if (!empty($pagesDashboard)) {
    // move dashlets from the dashboard to be at the end of the home screen dashlets
    foreach ($pagesDashboard[0]['columns'] as $dashboardColumnKey => $dashboardColumn) {
        foreach ($dashboardColumn['dashlets'] as $dashletItem) {
            $pages[0]['columns'][$dashboardColumnKey]['dashlets'][] = $dashletItem;
        }
    }
    $pages = array_merge($pages, $pagesDashboard);
    $current_user->setPreference('pages', $pages, 0, 'Home');
}
if (!empty($dashletsDashboard)) {
    $dashlets = array_merge($dashlets, $dashletsDashboard);
    $current_user->setPreference('dashlets', $dashlets, 0, 'Home');
}
if (!empty($pagesDashboard) || !empty($dashletsDashboard)) {
    $current_user->resetPreferences('Dashboard');
}

if (empty($pages)) {
    $pages = array();
    $pageIndex = 0;
    $pages[0]['columns'] = $columns;
    $pages[0]['numColumns'] = '3';
    $pages[0]['pageTitleLabel'] = 'LBL_HOME_PAGE_1_NAME'; // "My Sugar"
    $pageIndex++;
    $current_user->setPreference('pages', $pages, 0, 'Home');
    $activePage = 0;
}

$sugar_smarty = new Sugar_Smarty();

$activePage = 0;

$divPages[] = $activePage;

$numCols = $pages[$activePage]['numColumns'];

$count = 0;
$dashletIds = array(); // collect ids to pass to javascript
$display = array();

foreach ($pages[$activePage]['columns'] as $colNum => $column) {
    if ($colNum == $numCols) {
        break;
    }
    $display[$colNum]['width'] = $column['width'];
    $display[$colNum]['dashlets'] = array();
    foreach ($column['dashlets'] as $num => $id) {
        // clint - fixes bug #20398
        // only display dashlets that are from visibile modules and that the user has permission to list
        if (!empty($id) && isset($dashlets[$id]) && is_file($dashlets[$id]['fileLocation'])) {
            $module = 'Home';
            if (!empty($dashletsFiles[$dashlets[$id]['className']]['module'])) {
                $module = $dashletsFiles[$dashlets[$id]['className']]['module'];
            }
            // Bug 24772 - Look into the user preference for the module the dashlet is a part of in case
            //             of the Report Chart dashlets.
            elseif (!empty($dashlets[$id]['module'])) {
                $module = $dashlets[$id]['module'];
            }

            $myDashlet = new MySugar($module);

            if ($myDashlet->checkDashletDisplay()) {
                require_once $dashlets[$id]['fileLocation'];

                $dashlet = new $dashlets[$id]['className']($id, (isset($dashlets[$id]['options']) ? $dashlets[$id]['options'] : array()));
                // Need to add support to dynamically display/hide dashlets
                // If it has a method 'shouldDisplay' we will call it to see if we should display it or not
                if (method_exists($dashlet, 'shouldDisplay')) {
                    if (!$dashlet->shouldDisplay()) {
                        // This dashlet doesn't want us to show it, skip it.
                        continue;
                    }
                }

                array_push($dashletIds, $id);

                $dashlets = $current_user->getPreference('dashlets', 'Home');
// Using hardcoded 'Home' because DynamicAction.php $_REQUEST['module'] value is always Home
                $lvsParams = array();
                if (!empty($dashlets[$id]['sort_options'])) {
                    $lvsParams = $dashlets[$id]['sort_options'];
                }

                if (isset($pages[$activePage]['DMLocked']) && $pages[$activePage]['DMLocked']) {
                    $dashlet->is_locked = true;
                    $sugar_smarty->assign('lock_homepage', true);
                }

                // MintHCM #94842 START
                $dashlet_initial_loading = true;
                $dashlet->process($lvsParams, $id);
                $dashlet_initial_loading = false;
                // MintHCM #94842 STOP
                try {
                    $display[$colNum]['dashlets'][$id]['display'] = $dashlet->display();
                    $display[$colNum]['dashlets'][$id]['displayHeader'] = $dashlet->getHeader();
                    $display[$colNum]['dashlets'][$id]['displayFooter'] = $dashlet->getFooter();
                    if ($dashlet->hasScript) {
                        $display[$colNum]['dashlets'][$id]['script'] = $dashlet->displayScript();
                    }
                } catch (Exception $ex) {
                    $display[$colNum]['dashlets'][$id]['display'] = $ex->getMessage();
                    $display[$colNum]['dashlets'][$id]['displayHeader'] = $dashlet->getHeader();
                    $display[$colNum]['dashlets'][$id]['displayFooter'] = $dashlet->getFooter();
                }
            }
        }
    }
}

$i = 0;
while ($i < count($pages)) {
    if ($i == 0) {
        $pageTabs[$i]['pageTitle'] = $pages[$i]['pageTitle'] ? $pages[$i]['pageTitle'] : $GLOBALS['app_strings']['LBL_SUITE_DASHBOARD'];
//            $pageTabs[$i]['active'] = 'current';
    } else {
        $pageTabs[$i]['pageTitle'] = $pages[$i]['pageTitle'];
        $divPages[] = $i;
    }
    // MintHCM start #52883
    $pageTabs[$i]['DMForced'] = isset($pages[$i]['DMForced']) ? $pages[$i]['DMForced'] : 0;
    // MintHCM end #52883
    $i++;
}

if (!empty($sugar_config['lock_homepage']) && $sugar_config['lock_homepage'] == true) {
    $sugar_smarty->assign('lock_homepage', true);
}

$dashboardActions = $GLOBALS['app_strings']['LBL_SUITE_DASHBOARD_ACTIONS'];

$sugar_smarty->assign('sugarVersion', $sugar_version);
$sugar_smarty->assign('sugarFlavor', $sugar_flavor);
$sugar_smarty->assign('currentLanguage', $GLOBALS['current_language']);
$sugar_smarty->assign('serverUniqueKey', $GLOBALS['server_unique_key']);
$sugar_smarty->assign('imagePath', $GLOBALS['image_path']);

$sugar_smarty->assign('maxCount', empty($sugar_config['max_dashlets_homepage']) ? 15 : $sugar_config['max_dashlets_homepage']);
$sugar_smarty->assign('dashletCount', $count);
$sugar_smarty->assign('dashletIds', '["' . implode('","', $dashletIds) . '"]');
$sugar_smarty->assign('columns', $display);

global $theme;
$sugar_smarty->assign('theme', $theme);

$sugar_smarty->assign('divPages', $divPages);
$sugar_smarty->assign('activePage', $activePage);
$sugar_smarty->assign('dashboardPages', $pageTabs);
$sugar_smarty->assign('dashboardActions', $dashboardActions);
$sugar_smarty->assign('current_user', $current_user->id);

$sugar_smarty->assign('lblAdd', $GLOBALS['app_strings']['LBL_ADD_BUTTON']);
$sugar_smarty->assign('lblAddTab', $GLOBALS['app_strings']['LBL_ADD_TAB']);
$sugar_smarty->assign('lblAddDashlets', $GLOBALS['app_strings']['LBL_ADD_DASHLETS']);
$sugar_smarty->assign('lblLnkHelp', $GLOBALS['app_strings']['LNK_HELP']);

$sugar_smarty->assign('mod', return_module_language($GLOBALS['current_language'], 'Home'));
$sugar_smarty->assign('app', $GLOBALS['app_strings']);
$sugar_smarty->assign('module', 'Home');

//custom chart code
//Get the RGraph libraries (add this more elegantly later to check exactly what is needed, not just all).
require_once 'include/SuiteGraphs/RGraphIncludes.php';

require_once 'include/SugarCharts/SugarChartFactory.php';
$sugarChart = SugarChartFactory::getInstance();
if ($sugarChart) {
    $resources = $sugarChart->getChartResources();
    $mySugarResources = $sugarChart->getMySugarChartResources();
    $sugar_smarty->assign('chartResources', $resources);
    $sugar_smarty->assign('mySugarChartResources', $mySugarResources);
}

if (file_exists('custom/themes/' . $theme . '/tpls/MySugar.tpl')) {
    echo $sugar_smarty->fetch('custom/themes/' . $theme . '/tpls/MySugar.tpl');
} elseif (file_exists('custom/include/MySugar/tpls/MySugar.tpl')) {
    echo $sugar_smarty->fetch('custom/include/MySugar/tpls/MySugar.tpl');
} elseif (file_exists('themes/' . $theme . '/tpls/MySugar.tpl')) {
    echo $sugar_smarty->fetch('themes/' . $theme . '/tpls/MySugar.tpl');
} elseif (file_exists('include/MySugar/tpls/MySugar.tpl')) {
    echo $sugar_smarty->fetch('include/MySugar/tpls/MySugar.tpl');
} elseif (file_exists('custom/themes/' . $theme . '/include/MySugar/tpls/MySugar.tpl')) {
    echo $sugar_smarty->fetch('custom/themes/' . $theme . '/include/MySugar/tpls/MySugar.tpl');
} else {
    $GLOBALS['log']->fatal('MySugar.tpl not found');
}

//init the quickEdit listeners after the dashlets have loaded on home page the first time
echo "<script>if(typeof(qe_init) != 'undefined'){qe_init();}</script>";
echo "<script> $( '#pageNum_'+ 0 +'_anchor').addClass( 'current' );</script>";
echo "<script> $( '#pageNum_'+ 0).addClass( 'active' );</script>";
