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
 * Copyright (C) 2018-2019 MintHCM
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

/**
 * Returns result of custom Module Api method
 * EOU:
 * "callCustomApi( Accounts , getSomeInfo , param_1, param_2, ..., param_N )" where:
 * - Accounts - module name to find his Api class (here: AccountsApi) / this param is required
 * - getSomeInfo - method name to call in this Api class / this param is required
 * - param_1, param_2, ..., param_N - parameters given for method / this params are optional
 * Important info:
 * - location of Api: (custom/)modules/ModuleName/api/ModuleNameApi.php
 * -- example: modules/vt_Orders/api/vt_OrdersApi.php or custom/modules/Accounts/api/AccountsApi.php
 * - method called in this class should returns: boolean, integer, float, string, array
 * - if callCustomApi will not have first and second params (eg. Accounts, getSomeInfo) then callCustomApi returns false
 * - if called method doesn't exists or gives Fatal error then callCustomApi returns false
 */
class VTExpression_callCustomApi extends VTExpression {

   public $availability = array( 'vt_calculated', 'vt_dependency', 'vt_duplicate', 'vt_validation', 'related' );
   public $serversideFrontend = true;
   public $sqlBackendFormula = true;

   public function backend($arguments = array()) {
      $result = false;

      if ( count($arguments) >= 2 ) {
         $module_name = $this->cleanValue($arguments[0]);
         $method_name = $this->cleanValue($arguments[1]);
         array_shift($arguments);
         array_shift($arguments);
         $method_params = $this->cleanValuesFromArray($arguments);
         if ( !empty($_POST['is_frontend']) ) {
            if ( is_array($method_params[0]) ) {
               $method_params[0]['is_frontend'] = true;
            } elseif ( $method_params[0] == "" ) {
               $method_params[0] = [ 'is_frontend' => true ];
            }
         }

         if ( !empty($module_name) && !empty($method_name) ) {
            $api_class = $module_name . 'Api';
            $api_path = 'custom/modules/' . $module_name . '/api/' . $api_class . '.php';
            if ( !file_exists($api_path) ) {
               $api_path = 'modules/' . $module_name . '/api/' . $api_class . '.php';
            }
            if ( file_exists($api_path) ) {
               include_once $api_path;
               $api_instance = new $api_class();
               $result = call_user_func_array(array( $api_instance, $method_name ), $method_params);
            }
         }
      }
      return $result;
   }

   protected function cleanValuesFromArray($array) {
      $return_array = [];
      foreach ( $array as $key => $value ) {
         if ( is_array($value) ) {
            $return_array[$key] = $this->cleanValuesFromArray($value);
         } else {
            $return_array[$key] = $this->cleanValue($value);
         }
      }
      return $return_array;
   }

   protected function cleanValue($value) {
      $characters_to_clean = array( ' ', '\'', '"' );
      $old_value = '';
      while ( $old_value != $value ) {
         $old_value = $value;
         foreach ( $characters_to_clean as $character ) {
            $value = trim($value, $character);
         }
      }
      return $value;
   }

}
