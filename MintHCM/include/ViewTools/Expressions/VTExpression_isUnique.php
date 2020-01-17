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
 * Checks if given field_value of field_name is unique among other records of the same module
 * EOU:
 * "isUnique( name,\$name )" returns true, when $name is unique value of name field among other records of the same module, otherwise returns false
 */
class VTExpression_isUnique extends VTExpression {

   public $availability = array( 'vt_calculated', 'vt_dependency', 'vt_duplicate', 'vt_validation', 'related' );
   public $inputParams = array( 'field_name', 'field_value' );
   public $serversideFrontend = true;
   public $sqlBackendFormula = true;

   public function backend($arguments = array()) {
      $db = DBManagerFactory::getInstance();
      $module_name = VTExpression::getModuleName();
      $module_id = VTExpression::getRecordId();
      $bean = BeanFactory::newBean($module_name);
      $table_name = $bean->table_name;
      $field_name = $arguments['field_name'];
      $field_value = $arguments['field_value'];
      $result = true;
      if ( $field_name != '' && $field_value != '' ) {
         $sql = "SELECT id FROM $table_name WHERE $field_name = '$field_value' AND id != '$module_id' AND deleted = 0 LIMIT 1";
         $exist = $db->getOne($sql);
         if ( !empty($exist) ) {
            $result = false;
         }
      }
      return $result;
   }

}
