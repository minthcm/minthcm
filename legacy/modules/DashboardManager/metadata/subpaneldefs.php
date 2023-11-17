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

$layout_defs["DashboardManager"]["subpanel_setup"] = array(
   'users_locked_dashboards' => array(
      'order' => 1,
      'module' => 'Users',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_USERS_LOCKED_DASHBOARDS',
      'get_subpanel_data' => 'users_locked_dashboards',
      'top_buttons' =>
      array(
         array(
            'widget_class' => 'SubPanelTopSelectButtonForDM',
            'mode' => 'MultiSelect',
         ),
      ),
   ),
   'users_forced_tabs_dashboards' => array(
      'order' => 2,
      'module' => 'Users',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_USERS_FORCED_TABS_DASHBOARDS',
      'get_subpanel_data' => 'users_forced_tabs_dashboards',
      'top_buttons' =>
      array(
         array(
            'widget_class' => 'SubPanelTopSelectButtonForDM',
            'mode' => 'MultiSelect',
         ),
      ),
   ),
   'users_one_time_default_dashboards' => array(
      'order' => 3,
      'module' => 'Users',
      'subpanel_name' => 'default',
      'sort_order' => 'asc',
      'sort_by' => 'id',
      'title_key' => 'LBL_USERS_ONE_TIME_DEFAULT_DASHBOARDS',
      'get_subpanel_data' => 'users_one_time_default_dashboards',
      'top_buttons' =>
      array(
         array(
            'widget_class' => 'SubPanelTopSelectButtonForDM',
            'mode' => 'MultiSelect',
         ),
      ),
   ),
   'dashboardhistory' => array(
      'order' => 4,
      'module' => 'DashboardHistory',
      'subpanel_name' => 'default',
      'sort_order' => 'desc',
      'sort_by' => 'date_entered',
      'title_key' => 'LBL_DASHBOARDHISTORY',
      'get_subpanel_data' => 'dashboardhistory',
      'top_buttons' => array(
      ),
   ),
   'dashboardbackups' => array(
      'order' => 5,
      'module' => 'DashboardBackups',
      'subpanel_name' => 'default',
      'sort_order' => 'desc',
      'sort_by' => 'date_entered',
      'title_key' => 'LBL_DASHBOARDBACKUPS',
      'get_subpanel_data' => 'dashboardbackups',
      'top_buttons' => array(
      ),
   ),
);
