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
 * Checks if entered arrays are equal. First param is compared to rest of params.
 * EOU:
 * "arrayEquals($sales_stage, 'Completed', 'In Progress')"
 */
class VTExpression_arrayEquals extends VTExpression {

   /**
    * Variable used for creating documentation. As value, 
    * please set array of functions, which this formula can be used
    */
   public $availability = array( 'vt_calculated', 'vt_dependency', 'vt_required', 'vt_readonly', 'vt_duplicate', 'vt_validation' );

   /**
    * Warning! if backend is not set, return false
    * @param Array 
    * @return boolean
    * Please set input params as Array
    */
   public function backend($arguments = array()) {
      $first_array = array();
      if ( substr($arguments[0], -1) === '^' && substr($arguments[0], 0, 1) === '^' ) {
         $decoded = unencodeMultienum($arguments[0]);
         foreach ( $decoded as $value ) {
            $first_array[] = $value;
         }
      }
      $second_array = array();
      for ( $i = 1; $i < count($arguments); $i++ ) {
         $second_array[] = $arguments[$i];
      }
      $is_diff = false;
      foreach ( $first_array as $value ) {
         if ( !in_array($value, $second_array) ) {
            $is_diff = true;
            break;
         }
      }
      if ( !$is_diff ) {
         foreach ( $second_array as $value ) {
            if ( !in_array($value, $first_array) ) {
               $is_diff = true;
               break;
            }
         }
      }
      return !$is_diff;
   }

   /**
    * Warning! if frontend is not set, return false
    * @return type
    */
   public function frontend() {
      return <<<EOQ
      var first_array = arguments[0];
      var second_array = [];
      for(var i = 1; i<arguments.length; i++){
         second_array.push(arguments[i]);
      }
      var is_diff = false;
      if(Array.isArray(first_array) && Array.isArray(second_array)){
         first_array.forEach(function(a){ 
            if($.inArray(a, second_array) < 0){ 
               is_diff = true;
            }
         });
         if(!is_diff){
            second_array.forEach(function(a){
               if($.inArray(a, first_array) < 0){
                  is_diff = true;
               }
            });
         }
      } else {
         is_diff = true;
      }
      return !is_diff;
EOQ;
   }

   /**
    * Warning! if duplicate section is not set, return false
    */
   public function sqlbackend($arguments = array()) {
      $column = substr($this->interpreted_columns[0], 1);
      $parsed_string = substr(substr($arguments[0], 0, -1), 1);
      $first_array = explode("','", $parsed_string);
      $return = "( LENGTH( " . $column . " ) = LENGTH( '" . encodeMultienumValue($first_array) . "') AND {$column} LIKE '%^";
      $return .= implode("^%' AND {$column} LIKE '%^", $first_array) . "^%')";
      return $return;
   }

}
