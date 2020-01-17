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
 * First param is compared to rest of params. If first param will duplicate
 * in given params, function will return "true". Otherwise wi will get "false".
 * Second param can be also array.
 * EOU:
 * "inArray( 'John' , 'Tom' , 'David' , 'John' )" will give us "true"
 * "inArray( 'John' , 'Harry' , 'David' , 'Tom' )" will give us "false"
 * "inArray( 'John' , ['Harry' , 'David' , 'Tom'] )" will give us "false"
 * "inArray( 'Completed' , $accounting_subtype )" $accounting_subtype is multienum
 */
class VTExpression_inArray extends VTExpression {

   /**
    * Variable used for creating documentation. As value, 
    * please set array of functions, which this formula can be used
    */
   public $availability = array( 'vt_calculated', 'vt_dependency', 'vt_required', 'vt_readonly', 'vt_duplicate', 'vt_validation', 'related' );

   /**
    * Warning! if backend is not set, return false
    * @param Array 
    * @return boolean
    * Please set input params as Array
    */
   public function backend($arguments = array()) {
      $counter = 0;
      $compareArgument = false;
      if ( substr($arguments[1], -1) === '^' && substr($arguments[1], 0, 1) === '^' ) {
         $decoded = unencodeMultienum($arguments[1]);
         $key = 1;
         foreach ( $decoded as $value ) {
            $arguments[$key++] = $value;
         }
      }
      foreach ( $arguments as $argument ) {
         if ( $counter == 0 ) {
            $compareArgument = $argument;
         } else {
            if ( $compareArgument == $argument ) {
               return true;
            }
         }
         $counter++;
      }
      return false;
   }

   /**
    * Warning! if frontend is not set, return false
    * @return type
    */
   public function frontend() {
      return <<<EOQ
      var compareArgument = '';
      var args = [].slice.call(arguments);
      if(Array.isArray(args[1])){
         $.map( args[1], function( val, i ) {
            args.push(val);
         });
         args.splice(1, 1);
      }
      for ( var i = 0; i <= args.length; i++) {
         if( i == 0 ){
            compareArgument = args[i];
         }
         else{
            if( compareArgument == args[i] ){
               return true;
            }
         }
      }
      return false;
EOQ;
   }

   /**
    * Warning! if duplicate section is not set, return false
    */
   public function sqlbackend($arguments = array()) {
      $counter = 0;
      $field = false;
      $compare_fields = array();
      foreach ( $arguments as $argument ) {
         if ( $counter == 0 ) {
            $field = $argument;
         } else {
            $compare_fields[] = $argument;
         }
         $counter++;
      }
      return "{$field} IN (" . implode(',', $compare_fields) . ")";
   }

}
