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
 * Copyright (C) 2018-2019 MintHCM
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
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('include/MVC/View/views/view.detail.php');

class OnboardingTemplatesViewDetail extends ViewDetail
{

    public function preDisplay()
    {
        global $timedate, $current_user;
        parent::preDisplay();
        $cal_date_format  = $timedate->get_cal_date_format();
        $user_date_format = $timedate->get_user_date_format();
        $user_time_format = $timedate->get_user_time_format();
        $calendar_fdow    = $current_user->get_first_day_of_week();
        $time_separator   = ':';
        if (preg_match('/\d+([^\d])\d+([^\d]*)/s', $user_time_format, $match)) {
            $time_separator = $match[1];
        }
        $t23 = strpos($user_time_format, '23') !== false ? '%H' : '%I';
        if (!isset($match[2]) || empty($match[2])) {
            $calendar_format = $cal_date_format.' '.$t23.$time_separator.'%M';
        } else {
            $pm              = $match[2] === 'pm' ? '%P' : '%p';
            $calendar_format = $cal_date_format.' '.$t23.$time_separator.'%M'.$pm;
        }
        echo "
            <script>
                var _cal_date_format = '{$cal_date_format}';
                var _user_date_format = '{$user_date_format}';
                var _user_time_format = '{$user_time_format}';
                var _calendar_format = '{$calendar_format}';
                var _time_separator = '{$time_separator}';
                var _calendar_fdow = {$calendar_fdow};
            </script>
        ";
        $datetimecombo_js_file = 'include/SugarFields/Fields/Datetimecombo/Datetimecombo.js';
        if (file_exists('custom/'.$datetimecombo_js_file)) {
            $datetimecombo_js_file = 'custom/'.$datetimecombo_js_file;
        } else if (!file_exists($datetimecombo_js_file)) {
            $datetimecombo_js_file = '';
        }
        if (!empty($datetimecombo_js_file)) {
            echo "<script type='text/javascript' src='{$datetimecombo_js_file}'></script>";
        }
    }

    public function display()
    {
        parent::display();
        $generate_onboarding_offboarding_tpl = file_get_contents('modules/OnboardingTemplates/tpl/generateOnboardingOffboarding.tpl');
        echo "
            <script id='generate-onboarding-offboarding-template' type='text/template'>
                {$generate_onboarding_offboarding_tpl}
            </script>
        ";
    }
}