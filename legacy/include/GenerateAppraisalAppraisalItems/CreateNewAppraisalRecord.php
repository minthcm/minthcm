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
 * Copyright (C) 2018-2023 MintHCM
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

SugarAutoLoader::requireWithCustom('SetAppraisalRelatedModulesRelations.php');

class CreateNewAppraisalRecord {

   public $transformed_module_name = '';
   public $roles_beans = '';
   public $appraisal_name = '';
   protected static $RECRUITEMENT_MODULE_NAME = 'Recruitments';
   protected static $POSITIONS_MODULE_NAME = 'Positions';
   protected static $RESPONSIBIELITES_MODULE_NAME = 'Positions';
   protected static $BENEFITS_MODULE_NAME = 'Benefits';
   protected static $USERS_MODULE_NAME = 'Users';
   protected static $EMPLOYEES_MODULE_NAME = 'Employees';
   protected static $CANDIDATURES_MODULE_NAME = 'Candidatures';

   public function __construct($transformed_module_name, $appraisal_name) {
      $this->appraisal_name = $appraisal_name;
      $this->transformed_module_name = $transformed_module_name;
   }

   public function newAppraisal($transformed_record_bean) {
      $set_relations = new SetAppraisalRelatedModulesRelations();
      $appraisal_bean = $this->createAppraisalRecord($transformed_record_bean);

      if ( !empty($this->roles_beans) ) {
         $set_relations->setRelatedModulesRelations($this->roles_beans, $appraisal_bean);
      }

      return $appraisal_bean;
   }

   protected function createAppraisalRecord($transformed_record_bean) {
      global $current_user;

      $appraisal_bean = BeanFactory::newBean('Appraisals');
      $this->roles_beans = $this->getRolesBeans($transformed_record_bean);

      $appraisal_bean->assigned_user_id = $current_user->id;
      $appraisal_bean->candidature_id = $this->getCandidatureId($transformed_record_bean);
      $appraisal_bean->employee_id = $this->getEmployeeId($transformed_record_bean);
      $appraisal_bean->type = $this->getApprisalType();

      $appraisal_bean->name = $this->appraisal_name;
      $appraisal_bean->position_id = $this->getPositionId($transformed_record_bean);
      $appraisal_bean->status = 'planned';
      $appraisal_bean->save();

      return $appraisal_bean;
   }

   protected function getCandidatureId($transformed_record_bean) {
      return ($this->transformed_module_name == static::$CANDIDATURES_MODULE_NAME ? $transformed_record_bean->id : NULL);
   }

   protected function getApprisalType() {
      return ($this->transformed_module_name == static::$CANDIDATURES_MODULE_NAME ? 'recruiting' : 'other');
   }

   protected function getPositionId($transformed_record_bean) {
      $position_id = '';

      if ( $this->transformed_module_name == static::$EMPLOYEES_MODULE_NAME ) {
         $position_id = $transformed_record_bean->position_id;
      } elseif ( $this->transformed_module_name == static::$CANDIDATURES_MODULE_NAME ) {
         $recruitement_id = (empty($transformed_record_bean->recruitment_end_id) ? $transformed_record_bean->recruitment_id: $transformed_record_bean->recruitment_end_id);
         $recruitement_bean = BeanFactory::getBean(static::$RECRUITEMENT_MODULE_NAME, $recruitement_id);
         $position_id = $recruitement_bean->position_id;
      }

      return $position_id;
   }

   protected function getEmployeeId($transformed_record_bean) {
      return ($this->transformed_module_name == static::$EMPLOYEES_MODULE_NAME ? $transformed_record_bean->id : NULL);
   }

   protected function getRolesBeans($transformed_record_bean) {
      return ($this->transformed_module_name == static::$EMPLOYEES_MODULE_NAME ? $this->getRolesBeansFromEmployee($transformed_record_bean) : array());
   }

   protected function getRolesBeansFromEmployee($employee_bean) {
      $roles_beans = array();

      $linked_roles_from_employee = $employee_bean->get_linked_beans('roles', 'EmployeeRoles');
      if ( gettype($linked_roles_from_employee) == 'array' ) {
         $roles_beans = array_merge($roles_beans, $employee_bean->get_linked_beans('roles', 'EmployeeRoles'));
      } else {
         $roles_beans = array_merge($roles_beans, $employee_bean->get_linked_beans('roles', 'EmployeeRoles'));
      }
      return $roles_beans;
   }

}
