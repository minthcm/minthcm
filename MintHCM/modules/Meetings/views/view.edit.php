<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
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


require_once('include/json_config.php');

class MeetingsViewEdit extends ViewEdit {

   //MintHCM #44718 START
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

   /**
    * @see SugarView::preDisplay()
    *
    * Override preDisplay to check for presence of 'status' in $_REQUEST
    * This is to support the "Close And Create New" operation.
    */
   public function preDisplay() {
      if ( !empty($_REQUEST['status']) && ($_REQUEST['status'] == 'Held') ) {
         $this->bean->status = 'Held';
      }
      $this->assignStrings();
      $this->assignRepeatInterval();
      $this->assignRepeatIntervalJS();
      $this->assignDaysOfWeek();
      $this->assignShowEditAllRecurrences();

      parent::preDisplay();
   }

   //MintHCM #44718 END

   /**
    * @see SugarView::display()
    */
   public function display() {
      global $json;
      $json = getJSONobj();
      $json_config = new json_config();
      if ( isset($this->bean->json_id) && !empty($this->bean->json_id) ) {
         $javascript = $json_config->get_static_json_server(false, true, 'Meetings', $this->bean->json_id);
      } else {
         $this->bean->json_id = $this->bean->id;
         $javascript = $json_config->get_static_json_server(false, true, 'Meetings', $this->bean->id);
      }
      $this->ss->assign('JSON_CONFIG_JAVASCRIPT', $javascript);
      if ( $this->ev->isDuplicate ) {
         $this->bean->status = $this->bean->getDefaultStatus();
      } //if

      $this->ss->assign('remindersData', Reminder::loadRemindersData('Meetings', $this->bean->id, $this->ev->isDuplicate));
      $this->ss->assign('remindersDataJson', Reminder::loadRemindersDataJson('Meetings', $this->bean->id, $this->ev->isDuplicate));
      $this->ss->assign('remindersDefaultValuesDataJson', Reminder::loadRemindersDefaultValuesDataJson());
      $this->ss->assign('remindersDisabled', json_encode(false));

      parent::display();
   }

}
