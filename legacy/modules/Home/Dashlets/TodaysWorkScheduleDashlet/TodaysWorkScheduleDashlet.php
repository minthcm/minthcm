<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}


require_once('include/Dashlets/Dashlet.php');

class TodaysWorkScheduleDashlet extends Dashlet {

   public $savedText; // users's saved text
   public $height = '100'; // height of the pad

   /**
    * Constructor
    *
    * @global string current language
    * @param guid $id id for the current dashlet (assigned from Home module)
    * @param array $def options saved for this dashlet
    */
   public function __construct($id, $def) {
      $this->loadLanguage('TodaysWorkScheduleDashlet'); // load the language strings here

      if ( !empty($def['height']) ) { // set a default height if none is set
         $this->height = $def['height'];
      }

      parent::__construct($id); // call parent constructor
      $this->seedBean = BeanFactory::newBean('WorkSchedules');

      $this->isConfigurable = true; // dashlet is configurable
      $this->isRefreshable = true;
      $this->hasScript = true;  // dashlet has javascript attached to it
      // if no custom title, use default
      if ( empty($def['title']) ) {
         $this->title = $this->dashletStrings['LBL_TITLE'];
      } else {
         $this->title = $def['title'];
      }

      if ( isset($def['autoRefresh']) ) {
         $this->autoRefresh = $def['autoRefresh'];
      }
   }

   /**
    * Displays the dashlet
    *
    * @return string html to display dashlet
    */
   public function display() {
      $ss = new Sugar_Smarty();
      $this->assignVariablesToSmartyForDisplay($ss);
      $str = $ss->fetch(get_custom_file_if_exists('modules/Home/Dashlets/TodaysWorkScheduleDashlet/TodaysWorkScheduleDashlet.tpl'));
      $lbl = isset($this->dashletStrings['LBL_DBLCLICK_HELP']) ?
              $this->dashletStrings['LBL_DBLCLICK_HELP'] : '';
      return parent::display($lbl) . $str . '<br />' . $this->processAutoRefresh() . '<br />'; // return parent::display for title and such
   }

   protected function assignVariablesToSmartyForDisplay(Sugar_Smarty $ss) {
      global $current_user, $app_strings, $app_list_strings, $mod_strings;
      $ss->assign('id', $this->id);
      $ss->assign('height', $this->height);
      $ss->assign('dashletStrings', $this->dashletStrings);
      $ss->assign('firstDayOfWeek', $current_user->get_first_day_of_week());
      $ss->assign('current_user_is_admin', (is_admin($current_user) ? 1 : 0));
      $ss->assign('current_user_id', $current_user->id);
      $ss->assign('APP', $app_strings);
      $ss->assign('APPLIST', $app_list_strings);
      $ss->assign('MOD', $mod_strings);
   }

   /**
    * Displays the javascript for the dashlet
    *
    * @return string javascript to use with this dashlet
    */
   public function displayScript() {

      return '';
   }

   /**
    * Displays the configuration form for the dashlet
    *
    * @return string html to display form
    */
   public function displayOptions() {
      global $app_strings;

      $ss = new Sugar_Smarty();
      $ss->assign('titleLbl', $this->dashletStrings['LBL_CONFIGURE_TITLE']);
      $ss->assign('heightLbl', $this->dashletStrings['LBL_CONFIGURE_HEIGHT']);
      $ss->assign('saveLbl', $app_strings['LBL_SAVE_BUTTON_LABEL']);
      $ss->assign('clearLbl', $app_strings['LBL_CLEAR_BUTTON_LABEL']);
      $ss->assign('title', $this->title);
      $ss->assign('height', $this->height);
      $ss->assign('id', $this->id);

      if ( $this->isAutoRefreshable() ) {
         $ss->assign('isRefreshable', true);
         $ss->assign('autoRefresh', $GLOBALS['app_strings']['LBL_DASHLET_CONFIGURE_AUTOREFRESH']);
         $ss->assign('autoRefreshOptions', $this->getAutoRefreshOptions());
         $ss->assign('autoRefreshSelect', $this->autoRefresh);
      }

      return $ss->fetch(get_custom_file_if_exists('modules/Home/Dashlets/TodaysWorkScheduleDashlet/TodaysWorkScheduleOptions.tpl'));
   }

   /**
    * called to filter out $_REQUEST object when the user submits the configure dropdown
    *
    * @param array $req $_REQUEST
    * @return array filtered options to save
    */
   public function saveOptions($req) {
      global $sugar_config, $timedate, $current_user, $theme;
      $options = array();
      $options['title'] = $_REQUEST['title'];
      if ( is_numeric($_REQUEST['height']) ) {
         if ( $_REQUEST['height'] > 0 && $_REQUEST['height'] <= 300 ) {
            $options['height'] = $_REQUEST['height'];
         } elseif ( $_REQUEST['height'] > 300 ) {
            $options['height'] = '300';
         } else {
            $options['height'] = '100';
         }
      }

      $options['autoRefresh'] = empty($req['autoRefresh']) ? '0' : $req['autoRefresh'];
      $options['savedText'] = $this->savedText;

      return $options;
   }

   /**
    * Used to save text on textarea blur. Accessed via Home/CallMethodDashlet.php
    * This is an example of how to to call a custom method via ajax
    */
   public function saveText() {
      if ( isset($_REQUEST['savedText']) ) {
         $optionsArray = $this->loadOptions();
         $optionsArray['savedText'] = nl2br($_REQUEST['savedText']);
         $this->storeOptions($optionsArray);
      } else {
         $optionsArray['savedText'] = '';
      }
      $json = getJSONobj();
      echo 'result = ' . $json->encode(array(
         'id' => $_REQUEST['id'],
         'savedText' => $optionsArray['savedText']
         )
      );
   }

}
