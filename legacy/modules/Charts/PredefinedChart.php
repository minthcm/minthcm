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

/**

 * Description: Class defining queries of predefined charts.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 */



class PredefinedChart
{
    public $params = array();

    public function __construct()
    {
    }

    public function predefinedChartQuery($chart, $params=array())
    {
        switch ($chart) {
            case 'my_modules_used_last_30_days':
                return $this->myModuleUsageLast30Days();
            default:
                return $this->customChartQuery($chart);
        }
        return;
    }

    public function myModuleUsageLast30Days()
    {
        global $current_user;
        $dateValue = DBManagerFactory::getInstance()->convert("'".$timedate->getNow()->modify("-30 days")->asDb()."'", "datetime");

        $query  = "SELECT tracker.module_name as module_name ";
        $query .= ",COUNT(*) count FROM tracker ";
        $query .= "WHERE tracker.user_id = '$current_user->id' AND tracker.module_name != 'UserPreferences' AND tracker.date_modified > $dateValue ";
        $query .= "GROUP BY tracker.module_name ORDER BY count DESC";

        return $query;
    }


    // This function will grab a query from the custom directory to be used for charting
    public function customChartQuery($chart)
    {
        if (file_exists('custom/Charts/' . $chart . '.php')) {
            require_once('custom/Charts/' . $chart . '.php');
            return customChartQuery();
        } else {
            return false;
        }
    }
}
