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

class VTExpression {

   private static $record_id = null;
   private static $table_name = null;
   private static $module_name = null;
   private static $new_values = null;
   private static $action = null;
   /**
    * Variable used for creating documentation. As value, 
    * please set array of functions, which this formula can be used
    */
   public $availability = false;
   /**
    * if $inputParams are not set, return false
    * Definition of input params
    */
   public $inputParams = false;
   /**
    * If $serversideFrontend is true, frontend script declaration 
    * will be replaced with backend execution.
    * Warning! using frontend as $serversideFrontend too frequently
    * can strongly slow down module form.
    * Please use it carefully and on your own responsibility
    */
   public $serversideFrontend = false;
   /**
    * If $sqlBackendFormula is set to "true", backend formula 
    * will get sql "WHERE" definition from formulas 
    * defined inside "duplicate(formula(defined(inside)))"
    */
   public $sqlBackendFormula = false;

   /**
    * Warning! if backend is not set, return false
    * @param Array 
    * @return boolean
    * Please set input params as Array
    */
   public function backend($arguments = array()) {
      return false;
   }

   /**
    * Warning! if frontend is not set, return false
    * @param Array 
    * @return type
    * Please set input params as Array
    */
   public function frontend() {
      return false;
   }

   /**
    * Warning! if sqlbackend section is not set, return false
    * @param Array 
    * @return boolean
    * Please set input params as Array
    */
   public function sqlbackend($arguments = array()) {
      return false;
   }

   /**
    * Warinng!
    * Methods defined below shoult not be overwrited in extended classes
    */
   public function valueOf($value) {
      //Parse vardef validation fieldValue
      if ( substr($value, 0, 1) == '$' ) {
         $field = substr($value, 1, strlen($value) - 1);

         if ( isset($_REQUEST['convert_lead']) && $_REQUEST['convert_lead'] == true ) {
            $field = $_REQUEST['convert_lead_module'] . $field;
         }

         if ( !is_null(self::$new_values[$field]) ) {
            $value = self::$new_values[$field];
         } else if ( isset(self::$new_values[substr($field, 0, (strlen($field) - 5))]) && !is_null(self::$new_values[substr($field, 0, (strlen($field) - 5))]) ) {
            $value = self::$new_values[substr($field, 0, (strlen($field) - 5))];
         } else {
            return null;
         }
      }
      return $value;
   }

   /**
    * Get argument values from multi-level array
    * @param Array - multi-level array of arguments
    * @return Array - multi-level array of argument values
    */
   protected function retrieveValuesFromArray($array) {
      $return_array = [];
      foreach ( $array as $key => $value ) {
         if ( is_array($value) ) {
            $return_array[$key] = $this->retrieveValuesFromArray($value);
         } else {
            $return_array[$key] = $this->valueOf($value);
         }
      }
      return $return_array;
   }

   public function backendFormula($arguments) {
      //get values of parsed arguments
      foreach ( $arguments as $key => $argument ) {
         if ( is_array($argument) ) {
            $argument = $this->retrieveValuesFromArray($argument);
            $arguments[$key] = $argument;
         } else {
            $arguments[$key] = $this->valueOf($argument);
         }
      }
      //If input params are set, parse argument names
      if ( $this->inputParams !== false ) {
         $tmpArguments = array();
         foreach ( $this->inputParams as $key => $fieldName ) {
            $tmpArguments[$fieldName] = $arguments[$key];
         }
         $arguments = $tmpArguments;
      }
      return $this->backend($arguments);
   }

   /**
    * 
    */
   public function sqlFormula($arguments) {
      //get argument values
      foreach ( $arguments as $key => $argument ) {
         $tmpValue = $this->valueOf($arguments[$key]);
         //get value of field and put it in ' ' clause
         if ( $arguments[$key][0] == '$' ) {
            $this->interpreted_columns[] = $arguments[$key];
            if ( is_array($tmpValue) ) {
               $tmpValue = "'" . implode("','", $tmpValue) . "'";
            } else {
               $tmpValue = "'{$tmpValue}'";
            }
         }
         //Remove @ sign (only for column name definitions)
         else if ( $tmpValue[0] == '@' || $tmpValue[0] == '#' ) {
            $tmpValue = substr($tmpValue, 1);
         }
         $arguments[$key] = $tmpValue;
      }
      //If input params are set, parse argument names
      if ( $this->inputParams !== false ) {
         $tmpArguments = array();
         foreach ( $this->inputParams as $key => $fieldName ) {
            $tmpValue = $this->valueOf($arguments[$key]);
            $tmpArguments[$fieldName] = $tmpValue;
         }
         $arguments = $tmpArguments;
      }
      return $this->sqlbackend($arguments);
   }

   /**
    * Convert datetime from user format to sql format
    */
   public static function toSqlDateTime($field_value, $field_type) {
      global $timedate;

      $new_value = null;

      switch ( $field_type ) {
         case 'datetime':
         case 'datetimecombo':
            $new_value = $timedate->to_db($field_value);
            break;
         case 'date':
            $new_value = $timedate->to_db_date($field_value, false);
            break;
         case 'time':
            $new_value = $timedate->to_db_time($field_value);
            break;
         default:
            $new_value = $field_value;
            break;
      }
      return $new_value;
   }

   /**
    * QA - refactor needed
    * @param type $bean
    */
   public static function loadBeanValues(&$bean) {
      //Get actual values (before validation and save)
      self::$new_values = array();
      if ( !is_null($bean->field_defs) && is_array($bean->field_defs) ) {
         foreach ( array_keys($bean->field_defs) as $fieldName ) {
            self::loadValueForFieldBasedOnFetchedRowAndBeanValues($bean, $fieldName);
         }
      }
      if ( !is_null($bean->fetched_row['id']) ) {
         VTExpression::setRecordId($bean->fetched_row['id']);
      }
      if ( !is_null($bean->module_name) ) {
         VTExpression::setModuleName($bean->module_name);
      }
   }

   protected static function loadValueForFieldBasedOnFetchedRowAndBeanValues($bean, $fieldName) {
      if ( is_array($bean->fetched_row) && isset($bean->fetched_row[$fieldName]) && !is_null($bean->fetched_row[$fieldName]) ) {
         self::$new_values[$fieldName] = $bean->fetched_row[$fieldName];
      } else if ( is_array($bean->fetched_rel_row) && isset($bean->fetched_rel_row[$fieldName]) && !is_null($bean->fetched_rel_row[$fieldName]) ) {
         self::$new_values[$fieldName] = $bean->fetched_rel_row[$fieldName];
      } else {
         self::$new_values[$fieldName] = null;
      }
      //If new value was sent, update newValues array
      if ( isset($bean->$fieldName) && !is_null($bean->$fieldName) ) {
         self::$new_values[$fieldName] = $bean->$fieldName;
      }
   }

   public static function setValues($values = array()) {
      foreach ( $values as $field => $value ) {
         if ( is_array($value) ) {
            self::$new_values[$field] = $value;
         } else {
            self::$new_values[$field] = ( string ) $value;
         }
      }
   }

   /**
    * 
    * @global type $dictionary
    * @param reference $bean
    * @return Array
    */
   public static function getValidationFields(&$bean) {
      self::setTableName($bean->table_name);
      global $dictionary;
      $moduleFields = array();
      //Try to find validation definition for each field
      foreach ( self::$new_values as $field => $value ) {
         $dictionary_of_field = $dictionary[$bean->object_name]['fields'][$field];
         if ( !empty($dictionary_of_field['vt_validation']) ) {
            $validation = $dictionary_of_field['vt_validation'];
            //If validation definition was found - copy to temp arr
            $moduleFields[$field] = $validation;
         }
      }
      return $moduleFields;
   }

   /**
    * 
    * @global type $dictionary
    * @param reference $bean
    * @return Array
    */
   public static function getRequiredFields(&$bean) {
      self::setTableName($bean->table_name);
      global $dictionary;
      $moduleFields = array();
      //Try to find requirement definition for each field
      foreach ( self::$new_values as $field => $value ) {
         if ( !empty($dictionary[$bean->object_name]['fields'][$field]['vt_required']) ) {
            $moduleFields[$field] = $dictionary[$bean->object_name]['fields'][$field]['vt_required'];
         }
      }
      return $moduleFields;
   }

   /**
    * 
    * @global type $dictionary
    * @param reference $bean
    * @return Array
    */
   public static function getReadonlyFields(&$bean) {
      static::setTableName($bean->table_name);
      global $dictionary;
      $moduleFields = array();
      //Try to find readonly definition for each field
      foreach ( static::$new_values as $field => $value ) {
         if ( !empty($dictionary[$bean->object_name]['fields'][$field]['vt_readonly']) ) {
            $moduleFields[$field] = $dictionary[$bean->object_name]['fields'][$field]['vt_readonly'];
         }
      }
      return $moduleFields;
   }

   /**
    * 
    * @global type $dictionary
    * @param type $bean
    * @return Array
    */
   public static function getCalculatedFields(&$bean) {
      self::setTableName($bean->table_name);
      global $dictionary;
      $moduleFields = array();
      //Try to find calculated definition for each field
      foreach ( self::$new_values as $field => $value ) {
         if ( !empty($dictionary[$bean->object_name]['fields'][$field]['vt_calculated']) ) {
            $calculated = $dictionary[$bean->object_name]['fields'][$field]['vt_calculated'];
            //Check id vt_enforced is disabled, if yes - do not return calculated formula (keep value as is sent)
            if ( $dictionary[$bean->object_name]['fields'][$field]['vt_enforced'] != 'false' ) {
               //If calculated definition was found - copy to temp arr
               $moduleFields[$field] = $calculated;
            }
         }
      }
      return $moduleFields;
   }

   public static function getDependencyFields(&$bean) {
      self::setTableName($bean->table_name);
      global $dictionary;
      $moduleFields = array();
      //Try to find calculated definition for each field
      foreach ( self::$new_values as $field => $value ) {
         if ( !empty($dictionary[$bean->object_name]['fields'][$field]['vt_dependency']) ) {
            $moduleFields[$field] = $dictionary[$bean->object_name]['fields'][$field]['vt_dependency'];
         }
      }
      return $moduleFields;
   }

   public static function setTableName($table_name) {
      self::$table_name = $table_name;
   }

   public static function getTableName() {
      return self::$table_name;
   }

   public static function setRecordId($record_id) {
      self::$record_id = $record_id;
   }

   public static function getModuleName() {
      return self::$module_name;
   }

   public static function setModuleName($module_name) {
      self::$module_name = $module_name;
   }

   public static function getRecordId() {
      return self::$record_id;
   }

   public static function getValue($field_name) {
      return self::$new_values[$field_name];
   }

   public static function getAction() {
      return self::$action;
   }

   public static function setAction($action) {
      self::$action = $action;
   }

}
