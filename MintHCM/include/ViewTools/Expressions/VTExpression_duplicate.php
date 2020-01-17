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
 * Searches for duplicates
 * EOU:
 * "duplicate(equals(@name,$name))" will give us true if found record with @name same as record $name
 */
class VTExpression_duplicate extends VTExpression {

   /**
    * Variable used for creating documentation. As value, 
    * please set array of functions, which this formula can be used
    */
   public $availability = array( 'vt_calculated', 'vt_dependency', 'vt_required', 'vt_validation' );
   /**
    * if $inputParams are not set, return false
    * Definition of input params
    */
   public $inputParams = array( 'duplicate_formula' );
   /**
    * If $serversideFrontend is true, frontend script declaration 
    * will be replaced with backend execution.
    * Warning! using frontend as $serversideFrontend too frequently
    * can strongly slow down module form.
    * Please use it carefully and on your own responsibility
    */
   public $serversideFrontend = true;
   /**
    * If $sqlBackendFormula is set to "true", backend formula 
    * will get sql "WHERE" definition from formulas 
    * defined inside "duplicate(formula(defined(inside)))"
    */
   public $sqlBackendFormula = true;

   /**
    * Warning! if backend is not set, return false
    * @param Array 
    * @return boolean
    * Please set input params as Array
    */
   public function backend($arguments = array()) {
      global $db;

      SugarAutoLoader::requireWithCustom('include/ViewTools/Expressions/cache.php');
      SugarAutoLoader::requireWithCustom('include/ViewTools/Expressions/VTFormulaParser.php');
      SugarAutoLoader::requireWithCustom('include/ViewTools/Expressions/VTExpression.php');

      $duplicateQuery = 'SELECT * FROM ' . strtolower(VTExpression::getTableName());
      //Get where from formula definitions (build formula logic)
      $duplicateQuery = $duplicateQuery . ' WHERE (' . $arguments['duplicate_formula'] . ')';
      //If record is edited, exclude current record in duplicate check
      if ( VTExpression::getRecordId() != null && VTExpression::getRecordId() != '' ) {
         $duplicateQuery = $duplicateQuery . ' AND id != \'' . VTExpression::getRecordId() . '\'';
      }
      $duplicateQuery = $duplicateQuery . ' AND deleted = 0';
      $res = $db->query($duplicateQuery);
      //If found duplicate
      if ( $db->fetchByAssoc($res) !== false ) {
         return true;
      }
      return false;
   }

}
