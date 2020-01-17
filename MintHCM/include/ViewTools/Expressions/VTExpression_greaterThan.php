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
 * Checks if param1 is greater than param2.
 * EOU:
 * "greaterThan( 5 , 4 )" will give us "true"
 * "greaterThan( 5.4 , '5.9' )" will give us "false"
 * "greaterThan( 5.4 , floor( 5.9 ) )" will give us "true"
 * Within range example:
 * $value = 10 
 * "and( greaterThan( $value , 3 ) , greaterThan( 15 , $value ) )" will give us "true"
 */
class VTExpression_greaterThan extends VTExpression {

   /**
    * Variable used for creating documentation. As value, 
    * please set array of functions, which this formula can be used
    */
   public $availability = array( 'vt_calculated', 'vt_dependency', 'vt_required', 'vt_readonly', 'vt_duplicate', 'vt_validation', 'related' );
   /**
    * if $inputParams are not set, return false
    * Definition of input params
    */
   public $inputParams = array( 'param1', 'param2' );

   /**
    * Warning! if backend is not set, return false
    * @param Array 
    * @return boolean
    * Please set input params as Array
    */
   public function backend($arguments = array()) {
      $arguments['param1'] = floatval(unformat_number($arguments['param1']));
      $arguments['param2'] = floatval(unformat_number($arguments['param2']));
      if ( is_numeric($arguments['param1']) && is_numeric($arguments['param2']) && $arguments['param1'] > $arguments['param2'] ) {
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
      
      arguments['param1'] = unformatNumber( arguments['param1'], num_grp_sep, dec_sep );
      arguments['param2'] = unformatNumber( arguments['param2'], num_grp_sep, dec_sep );
      if(arguments['param1'] === ""){
         arguments['param1'] = 0;
      }
      if(arguments['param2'] === ""){
         arguments['param2'] = 0;
      }
      
      if ( arguments['param1'] === "" || isNaN( arguments['param1'] ) || arguments['param2'] === "" || isNaN( arguments['param2'] ) ) {
         return false;
      }
      if ( parseFloat( arguments['param1'] ) > parseFloat( arguments['param2'] ) ) {
         return true;
      }
      return false;
EOQ;
   }

   /**
    * Warning! if duplicate section is not set, return false
    */
   public function sqlbackend($arguments = array()) {
      return "{$arguments['param1']} > {$arguments['param1']}";
   }

}
