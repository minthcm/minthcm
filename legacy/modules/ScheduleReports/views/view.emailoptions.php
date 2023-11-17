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

require_once('include/MVC/View/SugarView.php');
require_once('include/EditView/EditView2.php');

/**
 * The class create view which we can change default email template used to send raports.
 */
class ScheduleReportsViewEmailOptions extends SugarView {

   var $ev;
   var $type = 'emailoptions';
   var $useForSubpanel = false;  //boolean variable to determine whether view can be used for subpanel creates
   var $useModuleQuickCreateTemplate = false; //boolean variable to determine whether or not SubpanelQuickCreate has a separate display function
   var $showTitle = true;
   var $path = 'custom/config/pdf_email_cfg.php';

   function ViewEmailOptions() {
      parent::SugarView();
   }

   function preDisplay() {
      parent::preDisplay();
      $metadataFile = $this->getMetaDataFile();
      $this->ev = new EditView();
      $this->ev->view = "emailoptionsView";
      $this->ev->ss = & $this->ss; //new Sugar_Smarty();
      $this->ev->setup($this->module, $this->bean, $metadataFile);
   }

   function display() {
      if ( $_REQUEST['action'] == 'saveOptions' ) {
         header("index.php?module=ScheduleReports&action=index");
         return;
      }
      if ( is_file($this->path) ) {
         require_once $this->path;
      }
      $this->bean->email_template_id = $options['email_template_id']['ScheduleReports'];
      $form_name = "OptionsView";
      $this->ev->formName = $form_name;
      $email_templates_arr = get_bean_select_array(true, 'EmailTemplate', 'name', '', 'name', true);
      $TMPL_DRPDWN_GENERATE = get_select_options_with_id($email_templates_arr, $this->bean->email_template_id);
      $PROFORMA_TMPL_DRPDWN_GENERATE = get_select_options_with_id($email_templates_arr, $this->bean->proforma_email_template_id);
      $this->ev->ss->assign('MOD', $mod_strings);
      $this->ev->ss->assign('APP', $app_strings);
      $this->ev->ss->assign("TMPL_DRPDWN_GENERATE", $TMPL_DRPDWN_GENERATE);
      $this->ev->ss->assign("PROFORMA_TMPL_DRPDWN_GENERATE", $PROFORMA_TMPL_DRPDWN_GENERATE);

      $this->ev->ss->assign("PDF_DIR", $this->bean->pdf_dir);
      $this->ev->process(true, $form_name);

      echo $this->ev->display();
   }

   protected function _getModuleTitleParams() {
      global $mod_strings;

      return array(
         "<a href='index.php?module=ScheduleReports&action=index'>" . translate('LBL_MODULE_NAME', 'ScheduleReports') . "</a>",
         $mod_strings['LBL_OPTIONS_TITLE']
      );
   }

}
