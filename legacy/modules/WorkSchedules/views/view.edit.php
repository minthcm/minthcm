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

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/MVC/View/views/view.edit.php');

class WorkSchedulesViewEdit extends ViewEdit {

   private function assignStrings() {
      global $app_list_strings, $mod_strings;
      $this->ss->assign('APPLIST', $app_list_strings);
      $this->ss->assign('MOD', $mod_strings);
   }

   private function assignRepeatInterval() {
      $repeat_intervals = array();
      for ( $i = 1; $i <= 30; $i++ ) {
         $repeat_intervals[$i] = $i;
      }
      $this->ss->assign("repeat_intervals", $repeat_intervals);
   }

   private function assignRepeatIntervalJS() {
      global $app_list_strings;
      $repeat_intervals_json = 'app_list_strings_repeat_intervals = {';
      foreach ( $app_list_strings['repeat_intervals'] as $k => $v ) {
         $repeat_intervals_json .= "'$k':'$v',";
      }
      $repeat_intervals_json .= '};';
      $this->ss->assign('ALSRI', $repeat_intervals_json);
   }

   private function assignDaysOfWeek() {
      global $current_user, $app_list_strings;
      $fdow = $current_user->get_first_day_of_week();
      $dow = array();
      for ( $i = $fdow; $i < $fdow + 7; $i++ ) {
         $day_index = $i % 7;
         $dow[] = array(
            "index" => $day_index,
            "label" => $app_list_strings['dom_cal_day_short'][$day_index + 1],
         );
      }
      $this->ss->assign("dow", $dow);
   }

   private function assignShowEditAllRecurrences() {
      $edit = (isset($_REQUEST['show_edit_all_recurrences']) && $_REQUEST['show_edit_all_recurrences']);
      $r = !$edit && $this->bean->repeat_type ? 1 : 0;
      $this->ss->assign('show_edit_all_recurrences', $r);
   }

   public function preDisplay() {

      $this->assignStrings();
      $this->assignRepeatInterval();
      $this->assignRepeatIntervalJS();
      $this->assignDaysOfWeek();
      $this->assignShowEditAllRecurrences();

      parent::preDisplay();
   }

   public function display() {
      global $current_user;
      $this->ev->ss->assign('CURRENT_USER_IS_ADMIN', is_admin($current_user));
      $this->ev->ss->assign('REDIRECTED_FROM_CALENDAR', 0);
      if ( isset($_GET['redirected_from_calendar']) ) {
         $this->ev->ss->assign('REDIRECTED_FROM_CALENDAR', 1);
      }
      if ( isset($_GET['return_module']) ) {
         $this->ev->ss->assign('RETURN_MODULE', $_GET['return_module']);
      }
      return parent::display();
   }

}
