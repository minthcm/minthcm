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
class AppraisalItems extends Basic {

   public $new_schema = true;
   public $module_dir = 'AppraisalItems';
   public $object_name = 'AppraisalItems';
   public $table_name = 'appraisalitems';
   public $importable = true;
   public $id;
   public $name;
   public $date_entered;
   public $date_modified;
   public $modified_user_id;
   public $modified_by_name;
   public $created_by;
   public $created_by_name;
   public $description;
   public $deleted;
   public $created_by_link;
   public $modified_user_link;
   public $assigned_user_id;
   public $assigned_user_name;
   public $assigned_user_link;
   public $SecurityGroups;
   public $value;
   public $parent_name;
   public $parent_type;
   public $parent_id;

   public function bean_implements($interface) {
      $result = false;
      if ( $interface === 'ACL' ) {
         $result = true;
      }
      return $result;
   }

   public function ACLAccess($view, $is_owner = 'not_set', $in_group = 'not_set') {
      $appraisal = BeanFactory::getBean('Appraisals');
      if ( $appraisal->retrieve($this->appraisal_id) ) {
         return $appraisal->ACLAccess($view, $is_owner, $in_group);
      }
      return parent::ACLAccess($view, $is_owner, $in_group);
   }

   public static function parentSave($bean) {
      $inline_appraisal_item_ids = $_REQUEST['inline_appraisal_item_id'];
      $appraisal_item_deleted_ids = $_REQUEST['appraisal_item_deleted'];
      if ( is_array($inline_appraisal_item_ids) ) {
         foreach ( $inline_appraisal_item_ids as $idx => $inline_appraisal_item_id ) {
            if ( isset($appraisal_item_deleted_ids[$idx]) && $appraisal_item_deleted_ids[$idx] == '0' ) {
               self::createAppraisalItemBean($bean, $idx, $inline_appraisal_item_id);
            } else {
               $appraisal_item = BeanFactory::newBean('AppraisalItems');
               $appraisal_item->mark_deleted($inline_appraisal_item_id);
            }
         }
      }
   }

   protected static function createAppraisalItemBean($parent_bean, $idx, $record_id = '') {
      $descriptions = $_REQUEST['appraisal_item_description'];
      $values = $_REQUEST['value'];
      $parent_types = $_REQUEST['parent_type'];
      $parent_names = $_REQUEST['parent_name'];
      $parent_ids = $_REQUEST['parent_id'];

      $appraisal_item = BeanFactory::getBean('AppraisalItems');

      if ( !empty($record_id) ) {
         $appraisal_item->retrieve($record_id);
      }

      if ( !empty($parent_bean->id) && !empty($parent_types[$idx]) && !empty($parent_ids[$idx]) && !empty($parent_names[$idx]) ) {
         $appraisal_item->appraisal_id = $parent_bean->id;
         $appraisal_item->appraisal_name = $parent_bean->name;
         $appraisal_item->parent_type = ( string ) $parent_types[$idx];
         $appraisal_item->parent_id = ( string ) $parent_ids[$idx];
         $appraisal_item->parent_name = ( string ) $parent_names[$idx];
         $appraisal_item->description = (!empty($descriptions[$idx])) ? ( string ) $descriptions[$idx] : '';
         $appraisal_item->value = (!empty($values[$idx])) ? ( string ) $values[$idx] : '';
         $appraisal_item->save();
      }
   }

   public static function parentDelete($bean) {
      global $db;
      $appraisal_item = BeanFactory::newBean('AppraisalItems');
      $sql = "SELECT id FROM appraisalitems WHERE appraisal_id = '{$bean->id}' AND deleted = 0";
      $result = $db->query($sql);
      while ( $row = $db->fetchByAssoc($result) ) {
         $appraisal_item->mark_deleted($row['id']);
      }
   }

}
