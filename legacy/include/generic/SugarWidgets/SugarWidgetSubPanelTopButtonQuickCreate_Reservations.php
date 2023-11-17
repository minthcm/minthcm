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

require_once 'include/generic/SugarWidgets/SugarWidgetSubPanelTopButtonQuickCreate.php';

class SugarWidgetSubPanelTopButtonQuickCreate_Reservations extends SugarWidgetSubPanelTopButtonQuickCreate {

   public function __construct(&$layout_manager) {
      parent::__construct($layout_manager);
      global $app_strings;
      $this->module = 'Reservations';
      $this->title = $app_strings['LBL_NEW_BUTTON_TITLE'];
      $this->access_key = $app_strings['LBL_NEW_BUTTON_KEY'];
      $this->form_value = translate('LBL_NEW_BUTTON_LABEL', $this->module);
      $this->acl = 'edit';
   }

   public function display($defines, $additionalFormFields = null, $nonbutton = false) {

      global $app_strings;
      $title = $app_strings['LBL_NEW_BUTTON_TITLE'];
      $value = $app_strings['LBL_NEW_BUTTON_LABEL'];
      $modules_to_change_dates = array( 'Delegations', 'Meetings' );
      $parent_bean = $defines['subpanel_definition']->parent_bean;
      $module_name = $parent_bean->module_name;

      if ( in_array($module_name, $modules_to_change_dates) ) {
         if ( $module_name == 'Delegations' ) {
            $end = $parent_bean->fetched_row['end_date'];
            $start = $parent_bean->fetched_row['start_date'];
         } elseif ( $module_name == 'Meetings' ) {
            $end = $parent_bean->fetched_row['date_end'];
            $start = $parent_bean->fetched_row['date_start'];
         }
         $additionalFormFields['starting_date'] = $start;
         $additionalFormFields['ending_date'] = $end;
      }


      $button = $this->_get_form($defines, $additionalFormFields);
      $button .= "<input title='$title' class='button' type='submit' " .
         "name='{$this->getWidgetId()}' id='{$this->getWidgetId()}' value='  $value  '/>\n";
      $button .= "</form>";


      return $button;
   }

}
