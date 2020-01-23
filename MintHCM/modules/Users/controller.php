<?php

require_once("include/OutboundEmail/OutboundEmail.php");

class UsersController extends SugarController {

   /**
    * bug 48170
    * Action resetPreferences gets fired when user clicks on  'Reset User Preferences' button
    * This action is set in UserViewHelper.php
    */
   protected function action_resetPreferences() {
      if ( $_REQUEST['record'] == $GLOBALS['current_user']->id || ($GLOBALS['current_user']->isAdminForModule('Users')) ) {
         $u = new User();
         $u->retrieve($_REQUEST['record']);
         $u->resetPreferences();
         if ( $u->id == $GLOBALS['current_user']->id ) {
            SugarApplication::redirect('index.php');
         } else {
            SugarApplication::redirect("index.php?module=Users&record=" . $_REQUEST['record'] . "&action=DetailView"); //bug 48170]
         }
      }
   }

   protected function action_delete() {
      if ( $_REQUEST['record'] != $GLOBALS['current_user']->id && ($GLOBALS['current_user']->isAdminForModule('Users')
         )
      ) {
         $u = new User();
         $u->retrieve($_REQUEST['record']);
         $u->status = 'Inactive';
         $u->employee_status = 'Terminated';
         $u->save();
         $u->mark_deleted($u->id);
         $GLOBALS['log']->info("User id: {$GLOBALS['current_user']->id} deleted user record: {$_REQUEST['record']}");

         $eapm = loadBean('EAPM');
         $eapm->delete_user_accounts($_REQUEST['record']);
         $GLOBALS['log']->info("Removing user's External Accounts");

         SugarApplication::redirect("index.php?module=Users&action=index");
      } else {
         sugar_die("Unauthorized access to administration.");
      }
   }

   protected function action_wizard() {
      $this->view = 'wizard';
   }

   protected function action_saveuserwizard() {
      global $current_user, $sugar_config;

      // set all of these default parameters since the Users save action will undo the defaults otherwise
      $_POST['record'] = $current_user->id;
      $_POST['is_admin'] = ($current_user->is_admin ? 'on' : '');
      $_POST['use_real_names'] = true;
      $_POST['reminder_checked'] = '1';
      $_POST['email_reminder_checked'] = '1';
      $_POST['reminder_time'] = 1800;
      $_POST['email_reminder_time'] = 3600;
      $_POST['mailmerge_on'] = 'on';
      $_POST['receive_notifications'] = $current_user->receive_notifications;
      $_POST['user_theme'] = (string) SugarThemeRegistry::getDefault();

      // save and redirect to new view
      $_REQUEST['return_module'] = 'Home';
      $_REQUEST['return_action'] = 'index';
      require('modules/Users/Save.php'); // MintHCM #62537
   }

   protected function action_saveftsmodules() {
      $this->view = 'fts';
      $GLOBALS['current_user']->setPreference('fts_disabled_modules', $_REQUEST['disabled_modules']);
   }

   protected function action_editview() {
      $this->view = 'edit';
      if ( !(is_admin($GLOBALS['current_user']) || $_REQUEST['record'] == $GLOBALS['current_user']->id) ) {
         SugarApplication::redirect("index.php?module=Home&action=index");
      }
   }

   protected function action_detailview() {
      $this->view = 'detail';
      if ( !(is_admin($GLOBALS['current_user']) || $_REQUEST['record'] == $GLOBALS['current_user']->id) ) {
         SugarApplication::redirect("index.php?module=Home&action=index");
      }
   }

   public static function getIDOfSubordinates(Array $user_ids) {
      global $db;
      $return = array();
      $sql = "SELECT id FROM users WHERE deleted=0 AND reports_to_id IN('" . implode("','", $user_ids) . "')";
      $r = $db->query($sql);
      while ( $a = $db->fetchByAssoc($r) ) {
         $return[] = $a['id'];
      }
      if ( !empty($return) ) {
         $return = array_merge($return, static::getIDOfSubordinates($return));
      }
      return array_unique($return);
   }

}
