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

class ViewToolsQueue {

   public $db;
   public $id;
   public $date_modified;
   public $module_name;
   public $record_id;

   const TABLE_NAME = 'view_tools_queue';

   public function __construct() {
      $this->db = DBManagerFactory::getInstance();
   }

   public function save() {
      if ( !empty($this->id) ) {
         $sql = "UPDATE " . self::TABLE_NAME . " SET id='{$this->id}', module_name='{$this->module_name}', record_id='{$this->record_id}', date_modified='{$GLOBALS['timedate']->nowDb()}' WHERE id='{$this->id}'";
      } else {
         $this->id = create_guid();
         $sql = "INSERT INTO " . self::TABLE_NAME . " (id, date_modified, module_name, record_id) VALUES ('" . $this->id . "','" . $GLOBALS['timedate']->nowDb() . "','{$this->module_name}','{$this->record_id}')";
      }
      if ( $this->db->query($sql) ) {
         return $this->id;
      } else {
         return false;
      }
   }

   public function delete($id) {
      return $this->db->query("DELETE FROM " . self::TABLE_NAME . " WHERE id='{$id}'");
   }

   public function retrieve($id) {
      if ( !empty($id) ) {
         $sql = "SELECT * FROM " . self::TABLE_NAME . " WHERE id='{$id}'";
         $row = $this->db->fetchOne($sql);
         foreach ( $row as $field_name => $value ) {
            $this->$field_name = $value;
         }
         return $this;
      } else {
         return false;
      }
   }

}
