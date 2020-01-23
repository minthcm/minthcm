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
 * Checks if entered value is numeric
 * EOU:
 * "isNumeric( 10 )" will give us "true"
 * "isNumeric( '10' )" will give us "true"
 * "isNumeric( subStr( 'ala ma 10 kotów' , 7 , 2 ) )" will also give us "true"
 */
class VTExpression_isNumeric extends VTExpression {

   /**
    * Variable used for creating documentation. As value, 
    * please set array of functions, which this formula can be used
    */
   public $availability = array( 'vt_calculated', 'vt_dependency', 'vt_required', 'vt_readonly', 'vt_duplicate', 'vt_validation', 'related' );
   /**
    * if $inputParams are not set, return false
    * Definition of input params
    */
   public $inputParams = array( 'value' );

   /**
    * Warning! if backend is not set, return false
    * @param Array 
    * @return boolean
    * Please set input params as Array
    */
   public function backend($arguments = array()) {
      $seps = get_number_seperators();
      $arguments['value'] = trim(str_replace(array( $seps[0], $seps[1] ), array( '', '.' ), $arguments['value']));
      if ( is_numeric($arguments['value']) ) {
         return true;
      }
      return false;
   }

   /**
    * Warning! if frontend is not set, return false
    * @return type
    */
   public function frontend() {
      return <<<EOQ
      if ( dec_sep == "." ) {
         dec_sep_reg = /\./g;
      } else {
         dec_sep_reg = dec_sep;
      }
      if ( num_grp_sep == "." ) {
         num_grp_sep_reg = /\./g;
      } else {
         num_grp_sep_reg = num_grp_sep;
      }
      var dec_regex = new RegExp( dec_sep_reg, 'g' );
      var num_regex = new RegExp( num_grp_sep_reg, 'g' );
      var value = arguments['value'].toString().trim();
      var replaced_value = value.replace( num_regex, '' ).replace( dec_regex, '.' );
      if ( replaced_value != "" && !isNaN( replaced_value ) ) {
         if ( value.indexOf( dec_sep ) != -1 ) {
            var split_value = value.split( dec_sep );
            if ( split_value[1].indexOf( num_grp_sep ) != -1 ) {
               return false;
            }
         }
         if( dec_sep != "." && num_grp_sep != "." && value.indexOf(".") != -1 ) {
            return false;
         }
         return true;
      }
      return false;
EOQ;
   }

   /**
    * Warning! if duplicate section is not set, return false
    */
   public function sqlbackend($arguments = array()) {
      return "TRIM({$arguments['value']}) REGEXP '^-?[0-9]+(\.|\,)?[0-9]?$'";
   }

}
