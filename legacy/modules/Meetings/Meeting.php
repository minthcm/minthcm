<?php

// MintHCM #54195

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

class Meeting extends SugarBean {

   // MintHCM #44718 START
   private static $repeatSaveRoudTripCounter = 0;
   // MintHCM #44718 END
   // Stored fields
   public $id;
   public $date_entered;
   public $date_modified;
   public $assigned_user_id;
   public $modified_user_id;
   public $created_by;
   public $created_by_name;
   public $modified_by_name;
   public $description;
   public $name;
   public $location;
   public $status;
   public $type;
   public $date_start;
   public $time_start;
   public $date_end;
   public $duration;
   public $duration_hours;
   public $duration_minutes;
   public $time_meridiem;
   public $parent_type;
   public $parent_type_options;
   public $parent_id;
   public $field_name_map;
   public $user_id;
   public $meeting_id;
   public $reminder_time;
   public $reminder_checked;
   public $email_reminder_time;
   public $email_reminder_checked;
   public $email_reminder_sent;
   public $required;
   public $accept_status;
   public $parent_name;
   public $account_id;
   public $opportunity_id;
   public $case_id;
   public $assigned_user_name;
   public $outlook_id;
   public $sequence;
   public $syncing = false;
   public $recurring_source;
   public $update_vcal = true;
   public $contacts_arr;
   public $users_arr;
   public $meetings_arr;
   // when assoc w/ a user/contact:
   public $minutes_value_default = 15;
   public $minutes_values = array('0' => '00', '15' => '15', '30' => '30', '45' => '45');
   public $table_name = "meetings";
   public $rel_users_table = "meetings_users";
   public $rel_contacts_table = "meetings_contacts";
   public $rel_leads_table = "meetings_leads";
   // MintHCM #54195 Start
   public $rel_candidates_table = "meetings_candidates";
   // MintHCM #54195 End
   public $module_dir = "Meetings";
   public $object_name = "Meeting";
   public $importable = true;
   // This is used to retrieve related fields from form posts.
   public $additional_column_fields = array('assigned_user_name', 'assigned_user_id', 'user_id', 'accept_status');
   public $relationship_fields = array(
      'assigned_user_id' => 'users',
       'user_id' => 'users', 
       'meeting_id' => 'meetings'
       );
   // so you can run get_users() twice and run query only once
   public $cached_get_users = null;
   public $new_schema = true;
   public $date_changed = false;
   public $repeat_parent_id;
   protected static $remindersInSaving = false;

   /**
    * sole constructor
    */
   public function __construct() {
      parent::__construct();
      $this->setupCustomFields('Meetings');
      foreach ( $this->field_defs as $field ) {
         $this->field_name_map[$field['name']] = $field;
      }
//		$this->fill_in_additional_detail_fields();
      if ( !empty($GLOBALS['app_list_strings']['duration_intervals']) ) {
         $this->minutes_values = $GLOBALS['app_list_strings']['duration_intervals'];
      }
   }

   /**
    * Disable edit if meeting is recurring and source is not Sugar. It should be edited only from Outlook.
    * @param $view string
    * @param $is_owner bool
    */
   public function ACLAccess($view, $is_owner = 'not_set', $in_group = 'not_set') {
      // don't check if meeting is being synced from Outlook
      if ( $this->syncing == false ) {
         $view = strtolower($view);
         switch ( $view ) {
            case 'edit':
            case 'save':
            case 'editview':
            case 'delete':
               if ( !empty($this->recurring_source) && $this->recurring_source != "Sugar" ) {
                  return false;
               }
         }
      }
      return parent::ACLAccess($view, $is_owner, $in_group);
   }

   /**
    * Stub for integration
    * @return bool
    */
   public function hasIntegratedMeeting() {
      return false;
   }

   // save date_end by calculating user input
   // this is for calendar
   public function save($check_notify = FALSE) {
      global $timedate;
      global $current_user;
      global $disable_date_format;

      if ( isset($this->date_start) ) {
         $td = $timedate->fromDb($this->date_start);
         if ( !$td ) {
            $this->date_start = $timedate->to_db($this->date_start);
            $td = $timedate->fromDb($this->date_start);
         }
         if ( $td ) {
            if ( isset($this->duration_hours) && $this->duration_hours != '' ) {
               $td->modify("+{$this->duration_hours} hours");
            }
            if ( isset($this->duration_minutes) && $this->duration_minutes != '' ) {
               $td->modify("+{$this->duration_minutes} mins");
            }
            $this->date_end = $td->asDb();
         }
      }

      $check_notify = (!empty($_REQUEST['send_invites']) && $_REQUEST['send_invites'] == '1') ? true : false;
      if ( empty($_REQUEST['send_invites']) ) {
         if ( !empty($this->id) ) {
            $old_record = BeanFactory::newBean('Meetings');
            $old_record->retrieve($this->id);
            $old_assigned_user_id = $old_record->assigned_user_id;
         }
         if ( (empty($this->id) && isset($_REQUEST['assigned_user_id']) && !empty($_REQUEST['assigned_user_id']) && $GLOBALS['current_user']->id != $_REQUEST['assigned_user_id']) || (isset($old_assigned_user_id) && !empty($old_assigned_user_id) && isset($_REQUEST['assigned_user_id']) && !empty($_REQUEST['assigned_user_id']) && $old_assigned_user_id != $_REQUEST['assigned_user_id']) ) {
            $this->special_notification = true;
            $check_notify = true;
            if ( isset($_REQUEST['assigned_user_name']) ) {
               $this->new_assigned_user_name = $_REQUEST['assigned_user_name'];
            }
         }
      }
      /* nsingh 7/3/08  commenting out as bug #20814 is invalid
        if($current_user->getPreference('reminder_time')!= -1 &&  isset($_POST['reminder_checked']) && isset($_POST['reminder_time']) && $_POST['reminder_checked']==0  && $_POST['reminder_time']==-1){
        $this->reminder_checked = '1';
        $this->reminder_time = $current_user->getPreference('reminder_time');
        } */

      // prevent a mass mailing for recurring meetings created in Calendar module
      if ( empty($this->id) && !empty($_REQUEST['module']) && $_REQUEST['module'] == "Calendar" && !empty($_REQUEST['repeat_type']) && !empty($this->repeat_parent_id) ) {
         $check_notify = false;
      }

      if ( empty($this->status) ) {
         $this->status = $this->getDefaultStatus();
      }

      // Do any external API saving
      // Clear out the old external API stuff if we have changed types
      if ( isset($this->fetched_row) && !is_bool($this->fetched_row) && $this->fetched_row['type'] != $this->type ) {
         $this->join_url = '';
         $this->host_url = '';
         $this->external_id = '';
         $this->creator = '';
      }

      if ( !empty($this->type) && $this->type != 'Sugar' ) {
         require_once('include/externalAPI/ExternalAPIFactory.php');
         $api = ExternalAPIFactory::loadAPI($this->type);
      }

      if ( empty($this->type) ) {
         $this->type = 'Sugar';
      }

      if ( isset($api) && is_a($api, 'WebMeeting') && empty($this->in_relationship_update) ) {
         // Make sure the API initialized and it supports Web Meetings
         // Also make suer we have an ID, the external site needs something to reference
         if ( !isset($this->id) || empty($this->id) ) {
            $this->id = create_guid();
            $this->new_with_id = true;
         }
         $response = $api->scheduleMeeting($this);
         if ( $response['success'] == TRUE ) {
            // Need to send out notifications
            if ( $api->canInvite ) {
               $notifyList = $this->get_notification_recipients();
               foreach ( $notifyList as $person ) {
                  $api->inviteAttendee($this, $person, $check_notify);
               }
            }
         } else {
            // Generic Message Provides no value to End User - Log the issue with message detail and continue
            // SugarApplication::appendErrorMessage($GLOBALS['app_strings']['ERR_EXTERNAL_API_SAVE_FAIL']);
            $GLOBALS['log']->warn('ERR_EXTERNAL_API_SAVE_FAIL' . ": " . $this->type . " - " . $response['errorMessage']);
         }

         $api->logoff();
      }

      $this->duration = $this->duration_hours . 'h ' . $this->duration_minutes . 'm';

      $return_id = parent::save($check_notify);

      // MintHCM #111604 start
      // dev note: whole condition should be moved to new frontend API in Mint4 in order to separate Controllers and Model
      if ( $this->shouldBeProcessedApi() ) {
        $this->createRelationshipFromApi();
        $this->saveRepeatlyApi();
      }
      // MintHCM #111604 end

      if ($this->status != $bean->fetched_row['status'] && $this->status == 'Held') {
         $this->closeRelatedTraining();
         }
         
      if ( $this->update_vcal ) {
         vCal::cache_sugar_vcal($current_user);
         // MintHCM start
         self::updateRelatedInvites($return_id);
         // MintHCM end
      }

      if ( isset($_REQUEST['reminders_data']) && !self::$remindersInSaving || isset($_REQUEST['reminders_data']) && empty($this->saving_reminders_data) ) {
        self::$remindersInSaving = true;
        $this->saving_reminders_data = true;
         $reminderData = json_encode(
            $this->removeUnInvitedFromReminders(json_decode(html_entity_decode($_REQUEST['reminders_data']), true))
         );
         Reminder::saveRemindersDataJson('Meetings', $return_id, $reminderData);
         self::$remindersInSaving = false;
         $this->saving_reminders_data = false;
      }


      return $return_id;
   }

   public function closeRelatedTraining(){
      global $db;
      $id = $this->id;
      $sql = "SELECT t.id FROM trainings AS t 
      JOIN trainings_meetings AS tm ON t.id = tm.training_id 
      JOIN meetings AS m ON tm.meeting_id = m.id 
      WHERE m.id = '{$id}' 
      AND t.status LIKE 'planned' AND t.deleted = 0";
         $result = $db->query($sql);
         while (($row = $db->fetchByAssoc($result)) != null) {
            $training_id = $row['id'];
            $sql_meetings = "SELECT m.id FROM meetings AS m 
            JOIN trainings_meetings AS tm ON m.id = tm.meeting_id 
            JOIN trainings AS m ON tm.training_id = t.id
            WHERE t.id = '{$training_id}' AND m.status LIKE 'Planned'";
            if(empty($db->getOne($sql_meetings))){
               $training_bean = BeanFactory::getBean('Trainings', $training_id);
               $training_bean->status = 'held';
               $training_bean->save();
            }
   }
}

   protected function postSave() {
      require_once 'modules/Candidatures/logic_hooks/CandidaturesLogicHook.php';
      $c = new CandidaturesLogicHook;
      $c->afterActivitySave($this);
      //
      require_once 'modules/Reservations/logic_hooks/ReservationsLogicHook.php';
      $r = new ReservationsLogicHook;
      $r->afterActivitySave($this);
   }

   protected function preMarkDeleted() {
      require_once 'modules/Reservations/logic_hooks/ReservationsLogicHook.php';
      $r = new ReservationsLogicHook;
      $r->beforeActivityDelete($this);
   }

   /**
    * @param array $reminders
    * @return array
    */
   public function removeUnInvitedFromReminders($reminders) {

      $reminderData = $reminders;
      $uninvited = array();
      foreach ( $reminders as $r => $reminder ) {
         foreach ( $reminder['invitees'] as $i => $invitee ) {
            switch ( $invitee['module'] ) {
               case "Users":
                  if ( in_array($invitee['module_id'], $this->users_arr) === false ) {
                     // add to uninvited
                     $uninvited[] = $reminderData[$r]['invitees'][$i];
                     // remove user
                     unset($reminderData[$r]['invitees'][$i]);
                  }
                  break;
               // MintHCM #54195 Start
               case "Candidates":
                  if ( in_array($invitee['module_id'], $this->candidates_arr) === false ) {
                     // add to uninvited
                     $uninvited[] = $reminderData[$r]['invitees'][$i];
                     // remove candidate
                     unset($reminderData[$r]['invitees'][$i]);
                  }
                  break;
               // MintHCM #54195 End
            }
         }
      }
      return $reminderData;
   }

   // this is for calendar
   public function mark_deleted($id) {

      require_once("modules/Calendar/CalendarUtils.php");
      CalendarUtils::correctRecurrences($this, $id);

      global $current_user;

      parent::mark_deleted($id);

      if ( $this->update_vcal ) {
         vCal::cache_sugar_vcal($current_user);
         // MintHCM start
         self::updateRelatedInvites($id);
         // MintHCM end
      }
   }

   // MintHCM start

   public static function updateRelatedInvites($meeting_id) {
      $db = DBManagerFactory::getInstance();

      $sql = "SELECT DISTINCT meetings_users.user_id "
         . "FROM meetings_users "
         . "INNER JOIN vcals ON meetings_users.user_id = vcals.user_id "
         . "WHERE meetings_users.accept_status = 'accept' AND meetings_users.meeting_id = '{$meeting_id}' ";

      $sql_rows = $db->query($sql);
      while ( $row = $db->fetchByAssoc($sql_rows) ) {
         $us = BeanFactory::getBean('Users', $row['user_id']);
         vCal::cache_sugar_vcal($us);
      }
   }

   // MintHCM end

   public function get_summary_text() {
        return (string)$this->name;
   }

   public function create_export_query($order_by, $where, $relate_link_join = '') {
      $custom_join = $this->getCustomJoin(true, true, $where);
      $custom_join['join'] .= $relate_link_join;

    $query = 'SELECT meetings.*, users.user_name as assigned_user_name  ';
    $query .= $custom_join['select'];
    $query .= ' FROM meetings ';
    $where_auto = "meetings.deleted=0";

      $query .= "  LEFT JOIN users ON meetings.assigned_user_id=users.id ";

      $query .= $custom_join['join'];

      if ( $where != "" ) {
         $query .= " where $where AND " . $where_auto;
      } else {
         $query .= " where " . $where_auto;
      }

      $order_by = $this->process_order_by($order_by);
      if ( !empty($order_by) ) {
         $query .= ' ORDER BY ' . $order_by;
      }

      return $query;
   }

   public function fill_in_additional_detail_fields() {
      global $locale;
      // Fill in the assigned_user_name
      $this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);

      $this->created_by_name = get_assigned_user_name($this->created_by);
      $this->modified_by_name = get_assigned_user_name($this->modified_user_id);
      $this->fill_in_additional_parent_fields();

      if ( !isset($this->time_hour_start) ) {
        $this->time_start_hour = (int)substr($this->time_start, 0, 2);
      } //if-else

      if ( isset($this->time_minute_start) ) {
         $time_start_minutes = $this->time_minute_start;
      } else {
         $time_start_minutes = substr($this->time_start, 3, 5);
         if ( $time_start_minutes > 0 && $time_start_minutes < 15 ) {
            $time_start_minutes = "15";
         } else if ( $time_start_minutes > 15 && $time_start_minutes < 30 ) {
            $time_start_minutes = "30";
         } else if ( $time_start_minutes > 30 && $time_start_minutes < 45 ) {
            $time_start_minutes = "45";
         } else if ( $time_start_minutes > 45 ) {
            $this->time_start_hour += 1;
            $time_start_minutes = "00";
         } //if-else
      } //if-else


      if ( isset($this->time_hour_start) ) {
         $time_start_hour = $this->time_hour_start;
      } else {
        $time_start_hour = (int)substr($this->time_start, 0, 2);
      }

      global $timedate;
      $this->time_meridiem = $timedate->AMPMMenu('', $this->time_start, 'onchange="SugarWidgetScheduler.update_time();"');
      $hours_arr = array();
      $num_of_hours = 13;
      $start_at = 1;

      if ( empty($time_meridiem) ) {
         $num_of_hours = 24;
         $start_at = 0;
      } //if

      for ( $i = $start_at; $i < $num_of_hours; $i ++ ) {
         $i = $i . "";
         if ( strlen($i) == 1 ) {
            $i = "0" . $i;
         }
         $hours_arr[$i] = $i;
      } //for

      if ( !isset($this->duration_minutes) ) {
         $this->duration_minutes = $this->minutes_value_default;
      }

      //setting default date and time
      if ( is_null($this->date_start) ) {
         $this->date_start = $timedate->now();
      }
      if ( is_null($this->time_start) ) {
         $this->time_start = $timedate->to_display_time(TimeDate::getInstance()->nowDb(), true);
      }
      if ( is_null($this->duration_hours) ) {
         $this->duration_hours = "0";
      }
      if ( is_null($this->duration_minutes) ) {
         $this->duration_minutes = "1";
      }

      if ( empty($this->id) && !empty($_REQUEST['date_start']) ) {
         $this->date_start = $_REQUEST['date_start'];
      }
      if ( !empty($this->date_start) ) {
         $td = SugarDateTime::createFromFormat($GLOBALS['timedate']->get_date_time_format(), $this->date_start);
         if ( !empty($td) ) {
            if ( !empty($this->duration_hours) && $this->duration_hours != '' ) {
               $td = $td->modify("+{$this->duration_hours} hours");
            }
            if ( !empty($this->duration_minutes) && $this->duration_minutes != '' ) {
               $td = $td->modify("+{$this->duration_minutes} mins");
            }
            $this->date_end = $td->format($GLOBALS['timedate']->get_date_time_format());
         } else {
            $GLOBALS['log']->fatal("Meeting::save: Bad date {$this->date_start} for format " . $GLOBALS['timedate']->get_date_time_format());
         }
      }

      global $app_list_strings;
      $parent_types = $app_list_strings['record_type_display'];
      $disabled_parent_types = ACLController::disabledModuleList($parent_types, false, 'list');
      foreach ( $disabled_parent_types as $disabled_parent_type ) {
         if ( $disabled_parent_type != $this->parent_type ) {
            unset($parent_types[$disabled_parent_type]);
         }
      }

      $this->parent_type_options = get_select_options_with_id($parent_types, $this->parent_type);
      if ( empty($this->reminder_time) ) {
         $this->reminder_time = -1;
      }

      if ( empty($this->id) ) {
         $reminder_t = $GLOBALS['current_user']->getPreference('reminder_time');
         if ( isset($reminder_t) ) {
            $this->reminder_time = $reminder_t;
         }
      }
      $this->reminder_checked = $this->reminder_time == -1 ? false : true;

      if ( empty($this->email_reminder_time) ) {
         $this->email_reminder_time = -1;
      }
      if ( empty($this->id) ) {
         $reminder_t = $GLOBALS['current_user']->getPreference('email_reminder_time');
         if ( isset($reminder_t) ) {
            $this->email_reminder_time = $reminder_t;
         }
      }
      $this->email_reminder_checked = $this->email_reminder_time == -1 ? false : true;

      if ( isset($_REQUEST['parent_type']) && empty($this->parent_type) ) {
         $this->parent_type = $_REQUEST['parent_type'];
      } elseif ( is_null($this->parent_type) ) {
         $this->parent_type = $app_list_strings['record_type_default_key'];
      }
   }

   public function get_list_view_data() {
      $meeting_fields = $this->get_list_view_array();

      global $app_list_strings, $focus, $action, $currentModule;
      if ( isset($this->parent_type) ) {
         $meeting_fields['PARENT_MODULE'] = $this->parent_type;
      }
      if ( $this->status == "Planned" ) {
         //cn: added this if() to deal with sequential Closes in Meetings.	this is a hack to a hack(formbase.php->handleRedirect)
         if ( empty($action) ) {
            $action = "index";
         }
         // MintHCM start #36257,#122649
         $setCompleteUrl = "<a id='meeting_{$this->id}' onclick='SUGAR.util.closeActivityPanel.show(\"{$this->module_dir}\",\"{$this->id}\",\"Held\",\"listview\",\"1\", this);'>";
         //$setCompleteUrl = "<a id='{$this->id}' onclick='SUGAR.util.closeActivityPanel.show(\"{$this->module_dir}\",\"{$this->id}\",\"Held\",\"listview\",\"1\");'>";
         // MintHCM end #36257,#122649
         if ( $this->ACLAccess('edit') ) {
            $meeting_fields['SET_COMPLETE'] = $setCompleteUrl . "<span class='suitepicon suitepicon-action-clear'></span></a></b>";
         } else {
            $meeting_fields['SET_COMPLETE'] = '';
         }
      }
      global $timedate;
      $today = $timedate->nowDb();
      $nextday = $timedate->asDbDate($timedate->getNow()->get("+1 day"));
      if ( !isset($meeting_fields['DATE_START']) ) {
         LoggerManager::getLogger()->warn('Meeting get list view data: Undefined index: DATE_START');
         $meetingFieldsDateStart = null;
      } else {
         $meetingFieldsDateStart = $meeting_fields['DATE_START'];
      }
      $mergeTime = $meetingFieldsDateStart;
      $date_db = $timedate->to_db($mergeTime);
      if ( $date_db < $today ) {
         if ( $meeting_fields['STATUS'] == 'Held' || $meeting_fields['STATUS'] == 'Not Held' ) {
            $meeting_fields['DATE_START'] = "<font>" . $meeting_fields['DATE_START'] . "</font>";
         } else {
            $meeting_fields['DATE_START'] = "<font class='overdueTask'>" . $meetingFieldsDateStart . "</font>";
         }
      } else if ( $date_db < $nextday ) {
         $meeting_fields['DATE_START'] = "<font class='todaysTask'>" . $meetingFieldsDateStart . "</font>";
      } else {
         $meeting_fields['DATE_START'] = "<font class='futureTask'>" . $meetingFieldsDateStart . "</font>";
      }
      $this->fill_in_additional_detail_fields();

      $meeting_fields['PARENT_NAME'] = $this->parent_name;
      $meeting_fields['REMINDER_CHECKED'] = $this->reminder_time == -1 ? false : true;
      $meeting_fields['EMAIL_REMINDER_CHECKED'] = $this->email_reminder_time == -1 ? false : true;


      return $meeting_fields;
   }

   public function set_notification_body($xtpl, &$meeting) {
      global $sugar_config;
      global $app_list_strings;
      global $current_user;
      global $timedate;

      if ( !isset($meeting->current_notify_user->object_name) ) {
         LoggerManager::getLogger()->warn('Meeting set_notification_body: Trying to get property of non-object ($meetingCurrentNotifyUserObjectName)');
         $meetingCurrentNotifyUserObjectName = null;
      } else {
         $meetingCurrentNotifyUserObjectName = $meeting->current_notify_user->object_name;
      }

      // cn: bug 9494 - passing a contact breaks this call
      $notifyUser = ($meetingCurrentNotifyUserObjectName == 'User') ?
         $meeting->current_notify_user :
         $current_user;

      // cn: bug 8078 - fixed call to $timedate

      if ( !isset($meeting->id) ) {
         LoggerManager::getLogger()->warn('Meeting set_notification_body: Trying to get property of non-object ($meetingId)');
         $meetingId = null;
      } else {
         $meetingId = $meeting->id;
      }

      if ( !isset($meeting->current_notify_user->id) ) {
         LoggerManager::getLogger()->warn('Meeting set_notification_body: Trying to get property of non-object ($meetingCurrentNotifyUserId)');
         $meetingCurrentNotifyUserId = null;
      } else {
         $meetingCurrentNotifyUserId = $meeting->current_notify_user->id;
      }

      if ( !is_object($meeting->current_notify_user) ) {
         LoggerManager::getLogger()->warn('Meeting try to set notification body but the current notify user is not an object');
      }

      if ( is_object($meeting->current_notify_user) && strtolower(get_class($meeting->current_notify_user)) == 'contact' ) {
         $xtpl->assign("ACCEPT_URL", $sugar_config['site_url'] .
            '/index.php?entryPoint=acceptDecline&module=Meetings&contact_id=' .
            $meetingCurrentNotifyUserId . '&record=' .
            $meetingId);
      } elseif ( is_object($meeting->current_notify_user) && strtolower(get_class($meeting->current_notify_user)) == 'lead' ) {
         $xtpl->assign("ACCEPT_URL", $sugar_config['site_url'] .
            '/index.php?entryPoint=acceptDecline&module=Meetings&lead_id=' .
            $meetingCurrentNotifyUserId . '&record=' .
            $meetingId);
      }
      // MintHCM #54195 Start
      elseif ( strtolower(get_class($meeting->current_notify_user)) == 'candidates' ) {
         $xtpl->assign("ACCEPT_URL", $sugar_config['site_url'] .
            '/index.php?entryPoint=acceptDecline&module=Meetings&candidate_id=' . $meeting->current_notify_user->id . '&record=' . $meeting->id);
      }
      // MintHCM #54195 End
      else {
         $xtpl->assign("ACCEPT_URL", $sugar_config['site_url'] .
            '/index.php?entryPoint=acceptDecline&module=Meetings&user_id=' .
            $meetingCurrentNotifyUserId . '&record=' .
            $meetingId);
      }


      if ( !isset($meeting->current_notify_user->new_assigned_user_name) ) {
         LoggerManager::getLogger()->warn('Meeting set_notification_body: Trying to get property of non-object ($meetingCurrentNotifyUserNewAssingnedUserName)');
         $meetingCurrentNotifyUserNewAssingnedUserName = null;
      } else {
         $meetingCurrentNotifyUserNewAssingnedUserName = $meeting->current_notify_user->new_assigned_user_name;
      }

      $xtpl->assign("MEETING_TO", $meetingCurrentNotifyUserNewAssingnedUserName);
      $xtpl->assign("MEETING_SUBJECT", trim($meeting->name));
      $xtpl->assign("MEETING_STATUS", (isset($meeting->status) ? $app_list_strings['meeting_status_dom'][$meeting->status] : ""));
      $typekey = strtolower($meeting->type);
      if ( isset($meeting->type) ) {
         if ( !empty($app_list_strings['eapm_list'][$typekey]) ) {
            $typestring = $app_list_strings['eapm_list'][$typekey];
         } else {
            $typestring = $app_list_strings['meeting_type_dom'][$meeting->type];
         }
      }
      $xtpl->assign("MEETING_TYPE", isset($meeting->type) ? $typestring : "");
      $startdate = $timedate->fromDb($meeting->date_start);
      $xtpl->assign("MEETING_STARTDATE", $timedate->asUser($startdate, $notifyUser) . " " . TimeDate::userTimezoneSuffix($startdate, $notifyUser));
      $enddate = $timedate->fromDb($meeting->date_end);
      $xtpl->assign("MEETING_ENDDATE", $timedate->asUser($enddate, $notifyUser) . " " . TimeDate::userTimezoneSuffix($enddate, $notifyUser));
      $xtpl->assign("MEETING_HOURS", $meeting->duration_hours);
      $xtpl->assign("MEETING_MINUTES", $meeting->duration_minutes);
      $xtpl->assign("MEETING_DESCRIPTION", nl2br($meeting->description));
      $xtpl->assign("MEETING_LOCATION", $meeting->location);
      if ( !empty($meeting->join_url) ) {
         $xtpl->assign('MEETING_URL', $meeting->join_url);
         $xtpl->parse('Meeting.Meeting_External_API');
      }

      return $xtpl;
   }

   /**
    * Redefine method to attach ics file to notification email
    */
   public function create_notification_email($notify_user) {
      // reset acceptance status for non organizer if date is changed
      if ( ($notify_user->id != $GLOBALS['current_user']->id) && $this->date_changed ) {
         $this->set_accept_status($notify_user, 'none');
      }

      $notify_mail = parent::create_notification_email($notify_user);

      $path = SugarConfig::getInstance()->get('upload_dir', 'upload/') . $this->id;

      require_once("modules/vCals/vCal.php");
      $content = vCal::get_ical_event($this, $GLOBALS['current_user']);

      if ( is_dir($path) ) {
         LoggerManager::getLogger()->warn('file_put_contents(' . $path . '): failed to open stream: Is a directory ');
      } else {
         if ( file_put_contents($path, $content) ) {
            $notify_mail->AddAttachment($path, 'meeting.ics', 'base64', 'text/calendar');
         }
      }
      return $notify_mail;
   }

   /**
    * Redefine method to remove ics after email is sent
    */
   public function send_assignment_notifications($notify_user, $admin) {
      parent::send_assignment_notifications($notify_user, $admin);

      $path = SugarConfig::getInstance()->get('upload_dir', 'upload/') . $this->id;

      if ( is_dir($path) ) {
         LoggerManager::getLogger()->warn('Meeting send_assignment_notifications: unlink(' . $path . '): Is a directory');
         return false;
      }

      return unlink($path);
   }

   public function get_meeting_users() {
    $template = BeanFactory::newBean('Users');
      // First, get the list of IDs.
      $query = "SELECT meetings_users.required, meetings_users.accept_status, meetings_users.user_id from meetings_users where meetings_users.meeting_id='$this->id' AND meetings_users.deleted=0";
      $GLOBALS['log']->debug("Finding linked records $this->object_name: " . $query);
      $result = $this->db->query($query, true);
      $list = Array();

      while ( $row = $this->db->fetchByAssoc($result) ) {
        $template = BeanFactory::newBean('Users');// PHP 5 will retrieve by reference, always over-writing the "old" one
         $record = $template->retrieve($row['user_id']);
         $template->required = $row['required'];
         $template->accept_status = $row['accept_status'];

         if ( $record != null ) {
            // this copies the object into the array
            $list[] = $template;
         }
      }
      return $list;
   }

   public function get_invite_meetings(&$user) {
      $template = $this;
      // First, get the list of IDs.
      $GLOBALS['log']->debug("Finding linked records $this->object_name: ");
      $query = "SELECT meetings_users.required, meetings_users.accept_status, meetings_users.meeting_id from meetings_users where meetings_users.user_id='$user->id' AND( meetings_users.accept_status IS NULL OR	meetings_users.accept_status='none') AND meetings_users.deleted=0";
      $result = $this->db->query($query, true);
      $list = Array();

      while ( $row = $this->db->fetchByAssoc($result) ) {
         $record = $template->retrieve($row['meeting_id']);
         $template->required = $row['required'];
         $template->accept_status = $row['accept_status'];


         if ( $record != null ) {
            // this copies the object into the array
            $list[] = $template;
         }
      }
      return $list;
   }

   public function set_accept_status(&$user, $status) {
      if ( $user->object_name == 'User' ) {
         $relate_values = array('user_id' => $user->id, 'meeting_id' => $this->id);
         $data_values = array('accept_status' => $status);
         $this->set_relationship($this->rel_users_table, $relate_values, true, true, $data_values);
         global $current_user;

         if ( $this->update_vcal ) {
            vCal::cache_sugar_vcal($user);
         }
      }
      // MintHCM #54195 Start
      else if ( $user->object_name == 'Candidates' ) {
         $relate_values = array('candidate_id' => $user->id, 'meeting_id' => $this->id);
         $data_values = array('accept_status' => $status);
         $this->set_relationship($this->rel_candidates_table, $relate_values, true, true, $data_values);
      }
      // MintHCM #54195 End
   }

   public function get_notification_recipients() {
      if ( $this->special_notification ) {
         return parent::get_notification_recipients();
      }

      $list = array();

      if ( !is_array($this->users_arr) ) {
         $this->users_arr = array();
      }

      // MintHCM #54195 Start
      if ( !is_array($this->candidates_arr) ) {
         $this->candidates_arr = array();
      }

      foreach ( $this->candidates_arr as $candidate_id ) {
         $notify_user = BeanFactory::getBean('Candidates', $candidate_id);
         $notify_user->new_assigned_user_name = $notify_user->full_name;
         $GLOBALS['log']->info("Notifications: recipient is $notify_user->new_assigned_user_name");
         $list[$notify_user->id] = $notify_user;
      }
      // MintHCM #54195 End

      foreach ( $this->users_arr as $user_id ) {
        $notify_user = BeanFactory::newBean('Users');
         $notify_user->retrieve($user_id);
         $notify_user->new_assigned_user_name = $notify_user->full_name;
         $GLOBALS['log']->info("Notifications: recipient is $notify_user->new_assigned_user_name");
         $list[$notify_user->id] = $notify_user;
      }

      global $sugar_config;
      if ( isset($sugar_config['disable_notify_current_user']) && $sugar_config['disable_notify_current_user'] ) {
         global $current_user;
         if ( isset($list[$current_user->id]) ) {
            unset($list[$current_user->id]);
         }
      }
      return $list;
   }

   public function bean_implements($interface) {
      switch ( $interface ) {
         case 'ACL':return true;
      }
      return false;
   }

   public function listviewACLHelper() {
      $array_assign = parent::listviewACLHelper();
      $is_owner = false;
      $in_group = false; //SECURITY GROUPS
      if ( !empty($this->parent_name) ) {

         if ( !empty($this->parent_name_owner) ) {
            global $current_user;
            $is_owner = $current_user->id == $this->parent_name_owner;
         }
         /* BEGIN - SECURITY GROUPS */
         //parent_name_owner not being set for whatever reason so we need to figure this out
         else if ( !empty($this->parent_type) && !empty($this->parent_id) ) {
            global $current_user;
            $parent_bean = BeanFactory::getBean($this->parent_type, $this->parent_id);
            if ( $parent_bean !== false ) {
               $is_owner = $current_user->id == $parent_bean->assigned_user_id;
            }
         }
         require_once("modules/SecurityGroups/SecurityGroup.php");
         $in_group = SecurityGroup::groupHasAccess($this->parent_type, $this->parent_id, 'view');
         /* END - SECURITY GROUPS */
      }

      /* BEGIN - SECURITY GROUPS */
      /**
        if(!ACLController::moduleSupportsACL($this->parent_type) || ACLController::checkAccess($this->parent_type, 'view', $is_owner)) {
       */
      if ( !ACLController::moduleSupportsACL($this->parent_type) || ACLController::checkAccess($this->parent_type, 'view', $is_owner, 'module', $in_group) ) {
         /* END - SECURITY GROUPS */
         $array_assign['PARENT'] = 'a';
      } else {
         $array_assign['PARENT'] = 'span';
      }

      $is_owner = false;
      $in_group = false; //SECURITY GROUPS

      /* BEGIN - SECURITY GROUPS */
      return $array_assign;
   }

   public function save_relationship_changes($is_update, $exclude = array()) {
      if ( empty($this->in_workflow) ) {
         if ( empty($this->in_import) ) {//if a meeting is being imported then contact_id  should not be excluded
            //if the global soap_server_object variable is not empty (as in from a soap/OPI call), then process the assigned_user_id relationship, otherwise
            //add assigned_user_id to exclude list and let the logic from MeetingFormBase determine whether assigned user id gets added to the relationship
            if ( !empty($GLOBALS['soap_server_object']) ) {
               $exclude = array('user_id');
            } else {
               $exclude = array('user_id', 'assigned_user_id');
            }
         } else {
            $exclude = array('user_id');
         }
      }
      parent::save_relationship_changes($is_update, $exclude);
   }

   /**
    * @see SugarBean::afterImportSave()
    */
   public function afterImportSave() {
      // MintHCM #54195 Start
      if ( $this->parent_type === 'Candidates' ) {
         $this->load_relationship('candidates');
         $this->candidates->add($this->parent_id);
      }
      // MintHCM #54195 End

      parent::afterImportSave();
   }

   public function getDefaultStatus() {
      $def = $this->field_defs['status'];
      if ( isset($def['default']) ) {
         return $def['default'];
      }
      $app = return_app_list_strings_language($GLOBALS['current_language']);
      if ( isset($def['options']) && isset($app[$def['options']]) ) {
         $keys = array_keys($app[$def['options']]);
         return $keys[0];
      }

      return '';
   }

   // MintHCM #44718 START
   public function shouldBeProcessed() {
      return !($_REQUEST['module'] != $this->module_name || self::$repeatSaveRoudTripCounter || empty($_REQUEST['repeat_type']) || empty($_REQUEST['date_start']));
   }

   public function saveRepeatly() {
      self::$repeatSaveRoudTripCounter++;
      require_once 'modules/Calendar/CalendarUtils.php';

      $params = array(
         'type' => $_REQUEST['repeat_type'],
         'interval' => $_REQUEST['repeat_interval'],
         'count' => $_REQUEST['repeat_count'],
         'until' => isset($_REQUEST['repeat_until']) ? $_REQUEST['repeat_until'] : null,
         'dow' => $_REQUEST['repeat_dow'],
      );

      $repeatArr = CalendarUtils::build_repeat_sequence($_REQUEST['date_start'], $params);
      $limit = SugarConfig::getInstance()->get('calendar.max_repeat_count', 1000);

      if ( !empty($_REQUEST['edit_all_recurrences']) ) {
         CalendarUtils::markRepeatDeleted($this);
      }

      if ( count($repeatArr) > ($limit - 1) ) {
         //$GLOBALS['log']->fatal('Repeat limit exceeded');
      } elseif ( isset($repeatArr) && is_array($repeatArr) && count($repeatArr) > 0 ) {
         CalendarUtils::save_repeat_activities($this, $repeatArr);
      }
   }
   // MintHCM #44718 END


   // MintHCM #111604 start
   protected function shouldBeProcessedApi() {
      return !( self::$repeatSaveRoudTripCounter || empty($this->repeat_type) || empty($this->date_start) || !empty($this->fetched_row)
      || empty($_SERVER['REQUEST_URI']) || substr($_SERVER['REQUEST_URI'], -14, 14) != "/Api/V8/module");
   }

   protected function createRelationshipFromApi()
   {
       
       if (!empty($this->users_arr) && is_string($this->users_arr) && $this->load_relationship('users') && $this->users) {
           $users_ids = explode(',', $this->users_arr);
           foreach ($users_ids as $user_id) {
               $this->users->add($user_id);
           }
       }
       
   }

   public function saveRepeatlyApi() {
      self::$repeatSaveRoudTripCounter++;
      require_once 'modules/Calendar/CalendarUtils.php';

      $params = array(
         'type' => $this->repeat_type,
         'interval' => $this->repeat_interval,
         'count' => $this->repeat_count,
         'until' => isset($this->repeat_until) ? $this->repeat_until : null,
         'dow' => $this->repeat_dow,
      );

      $date_start = $GLOBALS['timedate']->fromDb($this->date_start);
      if (!empty($date_start)) {
        $date_start = $GLOBALS['timedate']->tzUser($date_start);
        $repeatArr = CalendarUtils::build_repeat_sequence($date_start->format($GLOBALS['timedate']->get_date_time_format()), $params);
      } else {
        $repeatArr = CalendarUtils::build_repeat_sequence($this->date_start, $params);
      }
      $limit = SugarConfig::getInstance()->get('calendar.max_repeat_count', 1000);

      if ( !empty($this->edit_all_recurrences) ) {
         CalendarUtils::markRepeatDeleted($this);
      }

      if ( count($repeatArr) > ($limit - 1) ) {
         //$GLOBALS['log']->fatal('Repeat limit exceeded');
      } elseif ( isset($repeatArr) && is_array($repeatArr) && count($repeatArr) > 0 ) {
         CalendarUtils::save_repeat_activities($this, $repeatArr);
      }
   }
}
// MintHCM #111604 end

// end class def
// External API integration, for the dropdown list of what external API's are available
//TODO: do we really need focus, name and view params for this function
function getMeetingsExternalApiDropDown($focus = null, $name = null, $value = null, $view = null) {
   global $dictionary, $app_list_strings;

   $cacheKeyName = 'meetings_type_drop_down';

   $apiList = sugar_cache_retrieve($cacheKeyName);
   if ( $apiList === null ) {
      require_once('include/externalAPI/ExternalAPIFactory.php');

      $apiList = ExternalAPIFactory::getModuleDropDown('Meetings');
      $apiList = array_merge(array('Sugar' => $GLOBALS['app_list_strings']['eapm_list']['Sugar']), $apiList);
      sugar_cache_put($cacheKeyName, $apiList);
   }

   if ( !empty($value) && empty($apiList[$value]) ) {
      $apiList[$value] = $value;
   }
   //bug 46294: adding list of options to dropdown list (if it is not the default list)
   if ( !isset($dictionary['Meeting']) ) {
      LoggerManager::getLogger()->warn('Meeting getMeetingsExternalApiDropDown: Undefined index: Meeting ($dictionaryMeeting)');
      $dictionaryMeeting = null;
   } else {
      $dictionaryMeeting = $dictionary['Meeting'];
   }

    // Protect against null.
    if (
        is_null($dictionaryMeeting)
        || is_null($dictionaryMeeting['fields'])
        || is_null($dictionaryMeeting['fields']['type'])
        || $dictionaryMeeting['fields']['type']['options'] != "eapm_list"
    ) {
      $apiList = array_merge(getMeetingTypeOptions($dictionary, $app_list_strings), $apiList);
    }

   return $apiList;
}

/**
 * Meeting Type Options Array for dropdown list
 * @param array $dictionary - getting type name
 * @param array $app_list_strings - getting type options
 * @return array Meeting Type Options Array for dropdown list
 */
function getMeetingTypeOptions($dictionary, $app_list_strings) {
   $result = array();

   // getting name of meeting type to fill dropdown list by its values
   if ( isset($dictionary['Meeting']['fields']['type']['options']) ) {
      $typeName = $dictionary['Meeting']['fields']['type']['options'];

      if ( !empty($app_list_strings[$typeName]) ) {
         $typeList = $app_list_strings[$typeName];

         foreach ( $typeList as $key => $value ) {
            $result[$value] = $value;
         }
      }
   }

   return $result;
}
