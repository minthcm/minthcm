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

require_once 'include/ViewTools/ViewToolsQueue.php';

class Related {

   /**
    * Method used as GlobalHook
    * @global type $dictionary
    * @param SugarBean $bean
    * @param type $event
    * @param type $arguments
    */
   public function relatedRecalculation(SugarBean &$bean, $event = false, $arguments = false) {
      if(file_exists('include/ViewTools/Expressions/cache.php')){
         include('include/ViewTools/Expressions/cache.php');
         if ( isset($related_recalculation[$bean->table_name]) ) {
            if(file_exists('cache/Relationships/relationships.cache.php')){
               include ('cache/Relationships/relationships.cache.php');
               foreach ( array_unique($related_recalculation[$bean->table_name]) as $relationship ) {
                  if ( $bean->load_relationship($relationship) ) {
                     $related_module_name = $bean->$relationship->getRelatedModuleName();
                     $related_fields_names = $this->getCalculatedFieldNamesBasedOnRelatedModule($related_module_name, $relationship);
                     if ( $this->shouldChildrenBeResaved($bean, $related_fields_names) ) {
                        $ids = $bean->$relationship->get();
                        foreach ( $ids as $related_record_id ) {
                           $queue = new ViewToolsQueue();
                           $queue->module_name = $related_module_name;
                           $queue->record_id = $related_record_id;
                           $queue->save();
                        }
                     }
                  }
               }
            }
         }
      }
      return true;
   }

   protected function getCalculatedFieldNamesBasedOnRelatedModule($related_module_name, $relationship) {
      $related_fields = $this->getFieldDefsWhichContainCalculated($related_module_name);
      return $this->getNamesOfFieldsWhichinfluanceOnRelatedRecords($related_fields, $relationship);
   }

   protected function shouldChildrenBeResaved($bean, $related_fields_name) {
      $resave = false;
      foreach ( $related_fields_name as $field_name ) {
         if ( $bean->$field_name != $bean->fetched_row[$field_name] ) {
            $resave = true;
            break;
         }
      }
      return $resave;
   }

   protected function getFieldDefsWhichContainCalculated($module) {
      $focus = BeanFactory::getBean($module);
      $return_fields = [];
      foreach ( $focus->field_defs as $field_name => $def ) {
         if ( isset($def['vt_calculated']) ) {
            $return_fields[$field_name] = $def;
         }
      }
      return $return_fields;
   }

   protected function getNamesOfFieldsWhichinfluanceOnRelatedRecords($fields, $relationship_name) {
      $return_array = [];
      foreach ( $fields as $field_def ) {
         preg_match_all('/related\((.*?)\)/', $field_def['vt_calculated'], $matches);
         foreach ( $matches[1] as $match ) {
            $data = explode(',', $match);
            if ( $data[1] == '#' . $relationship_name ) {
               $return_array[] = substr($data[0], 1);
            }
         }
      }
      return $return_array;
   }

}
