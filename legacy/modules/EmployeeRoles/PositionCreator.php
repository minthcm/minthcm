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

class PositionCreator {

   const POSITIONS_MODULE_NAME = "Positions";
   const ROLES_MODULE_NAME = "EmployeeRoles";

   protected $record_id;

   public function __construct($record_id) {
      $this->record_id = $record_id;
   }

   public function create() {
      $role = BeanFactory::getBean(self::ROLES_MODULE_NAME);
      if ( $role->retrieve($this->record_id) ) {
         $position = BeanFactory::newBean(self::POSITIONS_MODULE_NAME);
         $position->name = $role->name;
         $position->status = $role->status;
         $position->assigned_user_id = $role->assigned_user_id;
         $position->save();
         $this->copy('benefits', $position);
         $this->copy('responsibilities', $position);
         $this->copyCompetencyRatings($position);
         return $position->id;
      }
   }

   protected function copy($module_name, $position) {
      global $db;
      $table_name = $module_name . "_employeeroles";
      $relationship_name = $module_name . "_positions";
      $id_field_name = array(
         'benefits' => 'benefit_id',
         'responsibilities' => 'responsibility_id',
      );
      $sql = "SELECT {$id_field_name[$module_name]} FROM {$table_name} WHERE role_id = '{$this->record_id}' AND deleted = 0";
      $result = $db->query($sql);
      $beans_ids = array();
      while ( $row = $db->fetchByAssoc($result) ) {
         $beans_ids[] = $row[$id_field_name[$module_name]];
      }
      if ( $position->load_relationship($relationship_name) ) {
         $position->$relationship_name->add($beans_ids);
      }
   }

   protected function copyCompetencyRatings($position) {
      global $db;
      $sql = "SELECT id FROM competencyratings WHERE parent_type = '" . self::ROLES_MODULE_NAME . "' AND parent_id = '{$this->record_id}' AND deleted = 0";
      $result = $db->query($sql);
      while ( $row = $db->fetchByAssoc($result) ) {
         $competency_rating = BeanFactory::getBean('CompetencyRatings');
         if ( $competency_rating->retrieve($row['id']) ) {
            $new_competency_rating = BeanFactory::newBean('CompetencyRatings');
            $new_competency_rating->rating = $competency_rating->rating;
            $new_competency_rating->competency_id = $competency_rating->competency_id;
            $new_competency_rating->competency_name = $competency_rating->competency_name;
            $new_competency_rating->assigned_user_id = $competency_rating->assigned_user_id;
            $new_competency_rating->parent_type = self::POSITIONS_MODULE_NAME;
            $new_competency_rating->parent_name = $position->name;
            $new_competency_rating->parent_id = $position->id;
            $new_competency_rating->save();
         }
      }
   }

}
