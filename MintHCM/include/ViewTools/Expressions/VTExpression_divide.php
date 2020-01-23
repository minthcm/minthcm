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
 * Return divide (float) of mixed values or <b>false</b> if any mixed value is not a number or second mixed value is zero. Empty ("") mixed means zero.
 * EOU:
 * "divide( 5 , "2" )" will give us 2.5
 * "divide( 0 , 5 )" will give us 0
 * "divide( 5 , 0 )" will give us "false"
 * "divide( "foo5" , "2" )" will give us "false"
 * "divide( "foo5" , "2" )" will give us "false"
 */
class VTExpression_divide extends VTExpression {

   /**
    * Variable used for creating documentation. As value, 
    * please set array of functions, which this formula can be used
    */
   public $availability = array( 'vt_calculated' );
   public $inputParams = array( 'mixed_1', 'mixed_2' );

   /**
    * Warning! if backend is not set, return false
    * @param Array 
    * @return float or false
    * Please set input params as Array
    */
   public function backend($arguments = array()) {
      $arguments['mixed_1'] = floatval(unformat_number($arguments['mixed_1']));
      $arguments['mixed_2'] = floatval(unformat_number($arguments['mixed_2']));
      if ( is_numeric($arguments['mixed_1']) && is_numeric($arguments['mixed_2']) && $arguments['mixed_2'] != 0.0 ) {
         return format_number(($arguments['mixed_1'] / $arguments['mixed_2']));
      }
      return false;
   }

   /**
    * Warning! if frontend is not set, return false
    * @return float or false
    */
   public function frontend() {
      return <<<EOQ
      arguments['mixed_1'] = unformatNumber( arguments['mixed_1'], num_grp_sep, dec_sep );
      arguments['mixed_2'] = unformatNumber( arguments['mixed_2'], num_grp_sep, dec_sep );
      if ( arguments['mixed_1'] == "" || isNaN( arguments['mixed_1'] ) || arguments['mixed_2'] == "" || isNaN( arguments['mixed_2'] || arguments['mixed_2'] == 0 ) ) {
         return false;
      }
      return formatNumber( (parseFloat( arguments['mixed_1'] ) / parseFloat( arguments['mixed_2'] )), num_grp_sep, dec_sep );
EOQ;
   }

   /**
    * Warning! if duplicate section is not set, return false
    */
   public function sqlbackend($arguments = array()) {
      return "({$arguments['mixed_1']} / {$arguments['mixed_2']})";
   }

}
