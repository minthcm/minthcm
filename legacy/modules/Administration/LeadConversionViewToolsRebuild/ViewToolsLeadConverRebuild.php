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

class ViewToolsLeadConverRebuild {

   protected $modules_used_in_lead_conversion = array();
   protected $initArray;

   public function __construct() {
      $path = 'modules/Leads/metadata/convertdefs.php';
      if ( file_exists('custom/' . $path) ) {
         include 'custom/' . $path;
      } else {
         include $path;
      }
      foreach ( $viewdefs as $module_name => $def ) {
         $this->modules_used_in_lead_conversion[] = $module_name;
      }
   }

   public function addLeadConversionDependency($initArray) {
      $this->initArray = $initArray;
      foreach ( $this->modules_used_in_lead_conversion as $module_used_in_conversion ) {
         if ( isset($this->initArray[strtolower($module_used_in_conversion)]) ) {
            $this->addModuleToLeadConversion($module_used_in_conversion);
         }
      }
      return $this->initArray;
   }

   protected function addModuleToLeadConversion($module) {
      foreach ( $this->initArray[strtolower($module)] as $field_name => $target_fields ) {
         $this->addFieldToModuleInLeadConversion($module, $field_name, $target_fields);
      }
   }

   protected function addFieldToModuleInLeadConversion($module, $field_name, $target_fields) {
      foreach ( $target_fields as $key => $value ) {
         $this->initArray['leads'][$module . $field_name][$module . $key] = $module . $value;
      }
   }

   public function addLeadConversionRequirements($initArray) {
      $this->initArray = $initArray;
      foreach ( $this->modules_used_in_lead_conversion as $module_used_in_conversion ) {
         if ( isset($this->initArray[strtolower($module_used_in_conversion)]) ) {
            $this->addModuleToLeadConversionRequirements($module_used_in_conversion);
         }
      }
      return $this->initArray;
   }

   protected function addModuleToLeadConversionRequirements($module) {
      foreach ( $this->initArray[strtolower($module)] as $field_name => $requirement_option ) {
         $this->initArray['leads'][$module . $field_name] = $requirement_option;
      }
   }

}
