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
 * Checks if PESEL is in proper format.
 * EOU:
 * "validatePESEL( '87121703434' )" will give us "true"
 */
class VTExpression_validatePESEL extends VTExpression {

   /**
    * Variable used for creating documentation. As value, 
    * please set array of functions, which this formula can be used
    */
   public $availability = array( 'vt_validation' );
   /**
    * if $inputParams are not set, return false
    * Definition of input params
    */
   public $inputParams = array( 'pesel' );

   /**
    * Warning! if backend is not set, return false
    * @param Array 
    * @return boolean
    * Please set input params as Array
    */
   public function backend($arguments = array()) {
      if ( isset($arguments['pesel']) && !empty($arguments['pesel']) ) {
         $pesel = preg_replace("/[^0-9]/", "", $arguments['pesel']);
         if ( strlen($pesel) == 11 ) {
            $pesel_array = str_split($pesel);
            $checksum = $this->countChecksum($pesel_array);
            return ( $checksum == $pesel_array[10] );
         }
      }
      return false;
   }

   protected function countChecksum($pesel_array) {
      $sum = 0;
      $weight_array = array( '1', '3', '7', '9', '1', '3', '7', '9', '1', '3' );
      for ( $i = 0; $i < 10; $i++ ) {
         $sum += $pesel_array[$i] * $weight_array[$i];
      }
      $checksum = $sum % 10;
      return (($checksum % 10 == 0) ? 0 : 10 - $checksum);
   }

   /**
    * Warning! if frontend is not set, return false
    * @return type
    */
   public function frontend() {
      return <<<EOQ
      var pesel = arguments['pesel'];
      pesel = pesel.replace(/\D/g,'');
      if (pesel.length == 11 ){
         var pesel_array = pesel.split("");
         var weight_array = ['1', '3', '7', '9', '1', '3', '7', '9', '1', '3'];
         var checksum = 0;
         for(i=0;i<10;i++) {
            checksum += pesel_array[i] * weight_array[i];
         }
         checksum = checksum % 10;
         checksum = (checksum == 0 ? 0: 10 - checksum);
         return (checksum == pesel_array[10]);
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
