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
 * Checks if REGON is in proper format.
 * EOU:
 * "validateREGON( '123456785' )" will give us "true"
 * "validateREGON( '12345678512347' )" will give us "true"
 */
class VTExpression_validateREGON extends VTExpression {

   /**
    * Variable used for creating documentation. As value, 
    * please set array of functions, which this formula can be used
    */
   public $availability = array( 'vt_validation' );
   /**
    * if $inputParams are not set, return false
    * Definition of input params
    */
   public $inputParams = array( 'regon' );

   /**
    * Warning! if backend is not set, return false
    * @param Array 
    * @return boolean
    * Please set input params as Array
    */
   public function backend($arguments = array()) {
      if ( isset($arguments['regon']) && !empty($arguments['regon']) ) {
         $regon = preg_replace("/[^0-9]/", "", $arguments['regon']);
         $lenght = strlen($regon);
         if ( $lenght == 9 || $lenght == 14 ) {
            $regon_array = str_split($regon);
            $checksum = $this->countChecksum($regon_array);
            return ($lenght == 9 ? $checksum == $regon_array[8] : $checksum == $regon_array[13]);
         }
      }
      return false;
   }

   protected function countChecksum($regon_array) {
      $sum = 0;
      $lenght = count($regon_array);
      if ( $lenght == 9 ) {
         $weight_array = array( '8', '9', '2', '3', '4', '5', '6', '7' );
      } else {
         $weight_array = array( '2', '4', '8', '5', '0', '9', '7', '3', '6', '1', '2', '4', '8' );
      }
      for ( $i = 0; $i < $lenght - 1; $i++ ) {
         $sum += $regon_array[$i] * $weight_array[$i];
      }
      $sum = $sum % 11;
      $sum = $sum % 10;
      return $sum;
   }

   /**
    * Warning! if frontend is not set, return false
    * @return type
    */
   public function frontend() {
      return <<<EOQ
      var regon = arguments['regon'];
      regon = regon.replace(/\D/g,'');
      var length = regon.length;
      if (length == 9 || length == 14 ){
         var regon_array = regon.split("");
         if (length == 9) {
            var weight_array = ['8', '9', '2', '3', '4', '5', '6', '7'];
         }  
         else {
            var weight_array = ['2', '4', '8', '5', '0', '9', '7', '3', '6', '1', '2', '4', '8'];
         }
         var checksum = 0;
         for(i=0;i<length-1;i++) {
            checksum += regon_array[i] * weight_array[i];
         }
         checksum = checksum % 11;
         checksum = checksum % 10;
         return (length == 9 ? checksum == regon_array[8] : checksum == regon_array[13]);
      }
      return false;
EOQ;
   }

   /**
    * Warning! if duplicate section is not set, return false
    */
   public function sqlbackend($arguments = array()) {
      return false;
   }

}
