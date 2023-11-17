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

class CreateNewAppraisalItemRecord {

   public $transformed_module_name = '';
   protected static $EMPLOYEES_MODULE_NAME = 'Employees';
   protected static $CANDIDATURES_MODULE_NAME = 'Candidatures';
   protected static $POSITIONS_MODULE_NAME = 'Positions';

   public function newAppraisalItem($transformed_record_bean, $appraisal_bean) {
      $this->transformed_module_name = $transformed_record_bean->module_name;

      $competencies = $this->getRelatedCompetencies($transformed_record_bean);
      $responsibilietes = $this->getRelatedResponsibilietes($transformed_record_bean, $appraisal_bean);

      $responsibilietes_n_competencies = array_merge($responsibilietes, $competencies);

      if ( !empty($responsibilietes_n_competencies) ) {
         foreach ( $responsibilietes_n_competencies as $one_elastic_module_bean ) {
            $this->createAppraisalItemRecord($transformed_record_bean, $one_elastic_module_bean, $appraisal_bean);
         }
      }
   }

   protected function createAppraisalItemRecord($transformed_record_bean, $elastic_module_bean, $appraisal_bean) {
      $appraisal_item_bean = BeanFactory::newBean('AppraisalItems');

      $appraisal_item_bean->parent_name = $elastic_module_bean->name;
      $appraisal_item_bean->appraisal_name = $appraisal_bean->name;

      $appraisal_item_bean->parent_type = $elastic_module_bean->module_name;
      $appraisal_item_bean->parent_id = $elastic_module_bean->id;
      $appraisal_item_bean->appraisal_id = $appraisal_bean->id;

      $appraisal_item_bean->save();
   }

   protected function getRelatedCompetencies($transformed_record_bean) {
      $competencies_beans = array();

      if ( $this->transformed_module_name == static::$EMPLOYEES_MODULE_NAME ) {
         $competencies_beans = $this->getRelatedCompetenciesFromEmployee($transformed_record_bean);
      } elseif ( $this->transformed_module_name == static::$CANDIDATURES_MODULE_NAME ) {
         $competencies_beans = $this->getRelatedCompetenciesFromCandidatures($transformed_record_bean);
      }

      return $competencies_beans;
   }

   protected function getRelatedCompetenciesFromEmployee($employee_bean) {
      $competencies_beans = array();
      $linked_competencies_ratings_from_employee = $employee_bean->get_linked_beans('competencyratings', 'CompetencyRatings');

      if ( !empty($linked_competencies_ratings_from_employee) ) {
         foreach ( $linked_competencies_ratings_from_employee as $one_competency_rating ) {
            $competencies_beans = array_merge($competencies_beans, $one_competency_rating->get_linked_beans('competencies', 'Competencies'));
         }
      }


      return $competencies_beans;
   }

   protected function getRelatedCompetenciesFromCandidatures($candidature_bean) {
      $competencies_beans = array();
      $linked_recruitement_from_candidature = '';

      if ( empty($linked_recruitement_from_candidature = $candidature_bean->get_linked_beans('recruitments_end', 'Recruitements')) ) {
         $linked_recruitement_from_candidature = $candidature_bean->get_linked_beans('recruitments', 'Recruitements');
      }

      $linked_position_from_recruitement = $linked_recruitement_from_candidature[0]->get_linked_beans('positions', 'Positions');
      $linked_competencies_rating_from_position = $linked_position_from_recruitement[0]->get_linked_beans('competencyratings', 'CompetencyRatings');

      if ( !empty($linked_competencies_rating_from_position) ) {
         foreach ( $linked_competencies_rating_from_position as $one_competency_rating ) {
            $competencies_beans = array_merge($competencies_beans, $one_competency_rating->get_linked_beans('competencies', 'Competencies'));
         }
      }
      return array_unique($competencies_beans, SORT_REGULAR);
   }

   protected function getRelatedResponsibilietes($transformed_record_bean, $appraisal_bean) {
      $reponsibilietes_beans = array();

      if ( $this->transformed_module_name == static::$EMPLOYEES_MODULE_NAME ) {
         $reponsibilietes_beans = $this->getRelatedResponsibilietesFromEmployee($appraisal_bean);
      } elseif ( $this->transformed_module_name == static::$CANDIDATURES_MODULE_NAME ) {
         $reponsibilietes_beans = $this->getRelatedResponsibilietesFromCandidatures($transformed_record_bean);
      }

      return $reponsibilietes_beans;
   }

   protected function getRelatedResponsibilietesFromEmployee($appraisal_bean) {
      $roles_beans = array();
      $reponsibilietes_beans = array();

      $linked_roles_beans = array_merge($roles_beans, $appraisal_bean->get_linked_beans('roles', 'EmployeeRoles'));

      if ( !empty($linked_roles_beans) ) {
         foreach ( $linked_roles_beans as $one_role_bean ) {
            $reponsibilietes_beans = array_merge($reponsibilietes_beans, $one_role_bean->get_linked_beans('responsibilities', 'Responsibilities'));
         }
      }

      $linked_position = $appraisal_bean->get_linked_beans('positions', static::$POSITIONS_MODULE_NAME);
      if ( !empty($linked_position) ) {
         $reponsibilietes_beans = array_merge($reponsibilietes_beans, $linked_position[0]->get_linked_beans('responsibilities', 'Responsibilities'));
      }

      return array_unique($reponsibilietes_beans, SORT_REGULAR);
   }

   protected function getRelatedResponsibilietesFromCandidatures($candidature_bean) {
      if ( empty($linked_recruitment_from_candidature = $candidature_bean->get_linked_beans('recruitments_end', 'Recruitements')) ) {
         $linked_recruitment_from_candidature = $candidature_bean->get_linked_beans('recruitments', 'Recruitements');
      }
      $linked_position_from_recruitement = $linked_recruitment_from_candidature[0]->get_linked_beans('positions', 'Positions');
      $linked_resposibilietes_from_position = $linked_position_from_recruitement[0]->get_linked_beans('responsibilities', 'Responsibilities');

      return $linked_resposibilietes_from_position;
   }

}
