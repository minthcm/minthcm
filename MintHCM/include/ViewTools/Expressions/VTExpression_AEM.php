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
 * Append error message
 * EOU:
 * "AEM(equals( 2 , 2 ),'LBL_ERROR')" will give us "true" and no error message
 * "AEM(equals( charAt( 'kot' , 1 ), 'o' ),'LBL_ERROR')" will give us "false" and will print error message
 */
class VTExpression_AEM extends VTExpression {

   /**
    * Variable used for creating documentation. As value, 
    * please set array of functions, which this formula can be used
    */
   public $availability = array( 'vt_validation' );
   /**
    * if $inputParams are not set, return false
    * Definition of input params
    */
   public $inputParams = array( 'formula', 'errorMessage' );

   /**
    * Warning! if backend is not set, return false
    * @param Array 
    * @return boolean
    * Please set input params as Array
    */
   public function backend($arguments = array()) {

      $ret = $arguments['formula'];
      if ( $ret === false || $ret === 'false' ) {
         SugarAutoLoader::requireWithCustom('include/ViewTools/Expressions/VTFormulaParser.php');
         $formula_parser = new VTFormulaParser();
         global $mod_strings;
         global $app_strings;

         $error_message = '';
         if ( isset($mod_strings[$arguments['errorMessage']]) ) {
            $error_message = $mod_strings[$arguments['errorMessage']];
         } else if ( isset($app_strings[$arguments['errorMessage']]) ) {
            $error_message = $app_strings[$arguments['errorMessage']];
         } elseif ( $arguments['errorMessage'] != '' ) {
            $error_message = $arguments['errorMessage'];
         }
         if ( $error_message != '' ) {
            SugarApplication::appendErrorMessage($error_message);
         }
      }
      return $ret;
   }

   /**
    * Warning! if frontend is not set, return false
    * @return type
    */
   public function frontend() {
      return <<<EOQ
      var ret = arguments['formula'];
      var error_message = '';
      
      if(ret==false||ret=='false'){
         var alert_msg = viewTools.language.get( window.viewTools.form.getModuleName($( 'form .vt_formulaSelector' ).last()), arguments['errorMessage'] );
         if( alert_msg!='undefined' && alert_msg!='' ){
            error_message = alert_msg;
         }
         else{
            alert_msg = viewTools.language.get( 'app_strings', arguments['errorMessage'] );
            if( alert_msg!='undefined' && alert_msg!='' ){
               error_message = alert_msg;
            }
            else if( arguments['errorMessage']!='undefined' && arguments['errorMessage']!='' ){
               error_message = arguments['errorMessage'];
            }
         }
         if( error_message!='' ){
            window.viewTools.cache.AEM.push(error_message);
         }
      }
      return ret;
EOQ;
   }

}
