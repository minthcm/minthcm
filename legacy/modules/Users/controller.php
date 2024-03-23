<?php

require_once __DIR__ . '/../../include/OutboundEmail/OutboundEmail.php';
require_once __DIR__ . '/../../modules/UserPreferences/UserPreference.php';

class UsersController extends SugarController {

   /**
    * bug 48170
    * Action resetPreferences gets fired when user clicks on  'Reset User Preferences' button
    * This action is set in UserViewHelper.php
    */
   protected function action_resetPreferences() {
      if ( $_REQUEST['record'] == $GLOBALS['current_user']->id || ($GLOBALS['current_user']->isAdminForModule('Users')) ) {
        $u = BeanFactory::newBean('Users');
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
        $u = BeanFactory::newBean('Users');
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

        // Will pull in the users details from first page of the wizard
        if (!empty($_POST['first_name'])) {
            $current_user->first_name = ($_POST['first_name']);
        }
        if (!empty($_POST['last_name'])) {
            $current_user->last_name = ($_POST['last_name']);
        }
        if (!empty($_POST['email1'])) {
            $current_user->email1 = ($_POST['email1']);
        }
        if (!empty($_POST['phone_work'])) {
            $current_user->phone_work = ($_POST['phone_work']);
        }
        if (!empty($_POST['phone_mobile'])) {
            $current_user->phone_mobile = ($_POST['phone_mobile']);
        }
        if (!empty($_POST['messenger_type'])) {
            $current_user->messenger_type = ($_POST['messenger_type']);
        }
        if (!empty($_POST['messenger_id'])) {
            $current_user->messenger_id = ($_POST['messenger_id']);
        }
        if (!empty($_POST['address_street'])) {
            $current_user->address_street = ($_POST['address_street']);
        }
        if (!empty($_POST['address_city'])) {
            $current_user->address_city = ($_POST['address_city']);
        }
        if (!empty($_POST['address_state'])) {
            $current_user->address_state = ($_POST['address_state']);
        }
        if (!empty($_POST['address_postalcode'])) {
            $current_user->address_postalcode = ($_POST['address_postalcode']);
        }
        if (!empty($_POST['address_country'])) {
            $current_user->address_country = ($_POST['address_country']);
        }

        // Saves User Details ONLY
        $current_user->save();


        // Will pull in the users Preferences from second page of the wizard
        if (!empty($_POST['timezone'])) {
            $current_user->setPreference('timezone', $_POST['timezone'],
                0, 'global');
        }
        if (!empty($_POST['dateformat'])) {
            $current_user->setPreference('dateformat', $_POST['dateformat'],
                0, 'global');
        }
        if (!empty($_POST['timeformat'])) {
            $current_user->setPreference('timeformat', $_POST['timeformat'],
                0, 'global');
        }
        if (!empty($_POST['currency'])) {
            $current_user->setPreference('currency', $_POST['currency'],
                0, 'global');
        }
        if (!empty($_POST['default_currency_significant_digits'])) {
            $current_user->setPreference('default_currency_significant_digits',
                $_POST['default_currency_significant_digits'], 0, 'global');
        }
        if (!empty($_POST['dec_sep'])) {
            $current_user->setPreference('dec_sep', $_POST['dec_sep'],
                0, 'global');
        }
        if (!empty($_POST['num_grp_sep'])) {
            $current_user->setPreference('num_grp_sep', $_POST['num_grp_sep'],
                0, 'global');
        }
        if (!empty($_POST['default_locale_name_format'])) {
            $current_user->setPreference('default_locale_name_format',
                $_POST['default_locale_name_format'], 0, 'global');
        }

        // redirect to home
        SugarApplication::redirect('index.php?action=index&module=Home');
        
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
