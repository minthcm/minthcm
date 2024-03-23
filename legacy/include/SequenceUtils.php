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

class SequenceUtils {

   protected const table_name = 'sequences';

   public function __construct() {
      $this->tryDatabase();
   }

   private function tryDatabase() {
      $this->db_name = $GLOBALS['sugar_config']['dbconfig']['db_name'];
      $this->db = DBManagerFactory::getInstance();
      $query = "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '" . $this->db_name .
         "' AND table_name = '" . static::table_name . "'";
      $row = $this->db->fetchByAssoc($this->db->query($query));
      $table_exists = $row['COUNT(*)'];

      if ( !$table_exists ) {
         $this->db->query("CREATE TABLE `" . $this->db_name . "`.`" . static::table_name .
            "` (`name` VARCHAR( 255 ) NOT NULL ,`value` INT NOT NULL DEFAULT  '1')");
      }
   }

   public function getNext($name) {
      $query = "SELECT * FROM `" . static::table_name . "` WHERE `name` = '" . $name . "'";
      $result = $this->db->query($query);
      $row = $this->db->fetchByAssoc($result);

      if ( $result->num_rows != 0 ) {
         $next = $row['value'] + 1;
         $this->db->query("UPDATE `" . $this->db_name . "`.`" . static::table_name .
            "` SET `value` = '" . $next . "' WHERE `" . static::table_name . "`.`name` = '" . $name . "' LIMIT 1 ;");
      } else {
         $next = 1;
         $this->db->query("INSERT INTO `" . $this->db_name . "`.`" . static::table_name .
            "` VALUES ('" . $name . "', '1');");
      }
      return $next;
   }

   public function getCurrent($name) {
      $query = "SELECT * FROM `" . static::table_name . "` WHERE `name` = '" . $name . "'";
      $result = $this->db->query($query);
      $row = $this->db->fetchByAssoc($result);
      $current = $row['value'];
      if ( $result->num_rows == 0 ) {
         $current = 1;
         $this->db->query("INSERT INTO `" . $this->db_name . "`.`" . static::table_name .
            "` VALUES ('" . $name . "', '1');");
      }
      return $current;
   }

   public function setValue($name, $value) {
      $query = "SELECT * FROM `" . static::table_name . "` WHERE `name` = '" . $name . "'";
      $result = $this->db->query($query);
      if ( $result->num_rows == 0 ) {
         $this->db->query("INSERT INTO `" . $this->db_name . "`.`" . static::table_name .
            "` VALUES ('" . $name . "', '" . $value . "');");
      } else {
         $this->db->query("UPDATE `" . $this->db_name . "`.`" . static::table_name .
            "` SET `value` = '" . $value . "' WHERE `" . static::table_name . "`.`name` = '" . $name . "' LIMIT 1 ;");
      }
   }

   public function decrement($name) {
      $query = "SELECT * FROM `" . static::table_name . "` WHERE `name` = '" . $name . "'";
      $result = $this->db->query($query);
      if ( $result->num_rows == 0 ) {
         $this->db->query("INSERT INTO `" . $this->db_name . "`.`" . static::table_name .
            "` VALUES ('" . $name . "', '0');");
      } else {
         $this->db->query("UPDATE `" . $this->db_name . "`.`" . static::table_name .
            "` SET `value` = `value`-1 WHERE `" . static::table_name . "`.`name` = '" . $name . "' LIMIT 1 ;");
      }
   }

}
