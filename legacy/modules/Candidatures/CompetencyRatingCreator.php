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

class CompetencyRatingCreator {

   protected $appraisal_item_module_bean = array();
   protected $competency_bean = '';
   protected $employee_bean = '';

   const COMPETENCY_RATING_MODULE_NAME = 'CompetencyRatings';
   const COMPETENCIES_MODULE_NAME = 'Competencies';
   const EMPLOYEES_MODULE_NAME = 'Employees';

   public function __construct($appraisal_item_bean, $employee_bean) {
      $this->appraisal_item_module_bean = $appraisal_item_bean;
      $this->competency_bean = BeanFactory::getBean(self::COMPETENCIES_MODULE_NAME, $appraisal_item_bean->parent_id);
      $this->employee_bean = $employee_bean;
   }

   public function createOrUpdateRecords() {
      $competency = $this->competency_bean;
      if ( $this->isThereOtherCompetencyRecordWithSameName() ) {
         $competency = $this->getComptenecyBeanWithSameName();
      }

      if ( !$this->wasAppraisalItemConverted($competency) ) {
         $bean = BeanFactory::newBean(self::COMPETENCY_RATING_MODULE_NAME);
         $bean->competency_name = $competency->name;
         $bean->parent_name = $this->employee_bean->first_name . ' ' . $this->employee_bean->last_name;
         $bean->parent_type = self::EMPLOYEES_MODULE_NAME;
         $bean->parent_id = $this->employee_bean->id;
         $bean->rating = $this->appraisal_item_module_bean->value;
         $bean->competency_id = $competency->id;
         $bean->save();
      } else {
         $bean = $this->getConvertedAppraisalItemBean($competency);
         $bean->rating = $this->appraisal_item_module_bean->value;
         $bean->save();
      }
   }

   protected function getConvertedAppraisalItemBean($competency_bean) {
      return BeanFactory::getBean(self::COMPETENCY_RATING_MODULE_NAME, $this->fetchCompetencyRatingIdCreatedFromAppraisalItem($competency_bean));
   }

   protected function wasAppraisalItemConverted($competency_bean) {
      return ( bool ) $this->fetchCompetencyRatingIdCreatedFromAppraisalItem($competency_bean);
   }

   protected function fetchCompetencyRatingIdCreatedFromAppraisalItem($competency_bean) {
      global $db;
      $competency_rating_parent_name = $this->employee_bean->first_name . ' ' . $this->employee_bean->last_name;
      $competency_name = $competency_bean->name;
      $competecy_rating_name = $competency_name . ' - ' . $competency_rating_parent_name;
      $sql = "SELECT id FROM competencyratings WHERE name ='{$competecy_rating_name}' AND competency_id='{$competency_bean->id}' AND deleted=0 ORDER BY date_entered DESC LIMIT 1";
      return $db->getOne($sql);
   }

   protected function isThereOtherCompetencyRecordWithSameName() {
      global $db;
      $sql = "SELECT id FROM competencies WHERE name='{$this->competency_bean->name}' AND  id<>'{$this->competency_bean->id}' AND deleted=0 ORDER BY date_entered ASC LIMIT 1";
      return ( bool ) $db->getOne($sql);
   }

   protected function getComptenecyBeanWithSameName() {
      global $db;
      $sql = "SELECT id FROM competencies WHERE name='{$this->competency_bean->name}' AND id<>'{$this->competency_bean->id}' AND deleted=0 ORDER BY date_entered ASC LIMIT 1";
      $competency_id = $db->getOne($sql);
      return BeanFactory::getBean(self::COMPETENCIES_MODULE_NAME, $competency_id);
   }

}
