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

require_once('modules/KTemplates/hooks_controller.php');

class KTemplates extends Basic {

   public $new_schema = true;
   public $module_dir = 'KTemplates';
   public $object_name = 'KTemplates';
   public $table_name = 'ktemplates';
   public $importable = false;
   public $disable_row_level_security = true; // to ensure that modules created and deployed under CE will continue to function under team security if the instance is upgraded to PRO
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
   public $template;
   public $relatedmodule;
   public $is_default = 0;

   public function __construct() {
      parent::__construct();
   }

   public function bean_implements($interface) {
      if ( "ACL" === $interface ) {
         return true;
      } else {
         return false;
      }
   }

   public function save($check_notify = FALSE) {
      // need to call hook before Sute 7.10 cleanBean function - it cleans <!--repeat--> tags
      if ( empty($this->id) ) {
         $this->id = create_guid();
         $this->new_with_id = true;
      }
      $this->saveTemplate();
      $return_id = parent::save($check_notify);

      //save template to config file
      $path = 'modules/KReports/Plugins/Integration/kpdfexport/templates/config.php';
      include $path;
      if ( !isset($relation_config[$this->relatedmodule]) ) {
         $relation_config[$this->relatedmodule] = array();
      }
      if ( array_search($this->id, $relation_config[$this->relatedmodule]) === false ) {
         $is_default = array_search("Default", $relation_config[$this->relatedmodule]);
         if ( $is_default === 0 )
            $is_default = true;
         if ( $is_default == false ) {
            $relation_config[$this->relatedmodule][] = 'Default';
         }
         $relation_config[$this->relatedmodule][] = $this->id;
         $relation_config[$this->relatedmodule] = $this->my_sort_array($relation_config[$this->relatedmodule]);
         write_array_to_file('relation_config', $relation_config, $path);
      }
      SugarApplication::redirect("index.php?module=KTemplates&action=DetailView&record={$this->id}");
      return $return_id;
   }

   public function mark_deleted($id) {
      $path = 'modules/KReports/Plugins/Integration/kpdfexport/templates/config.php';
      include $path;
      $key = array_search($this->id, $relation_config[$this->relatedmodule]);
      unset($relation_config[$this->relatedmodule][$key]);
      $fp = sugar_fopen($path, 'w');
      fclose($fp);
      $relation_config[$this->relatedmodule] = $this->my_sort_array($relation_config[$this->relatedmodule]);
      write_array_to_file('relation_config', $relation_config, $path);
      parent::mark_deleted($id);
   }

   function my_sort_array($array) {
      $return_array = array();
      foreach ( $array as $value ) {
         $return_array[] = $value;
      }
      return $return_array;
   }

   protected function saveTemplate() {
      if ( $this->is_default ) {
         $query = "UPDATE `ktemplates` SET `is_default` = '0' WHERE `relatedmodule`='" . $bean->relatedmodule . "' AND `is_default`=1 AND `deleted`=0";
         $result = $this->db->query($query);
      }
      $this->template = SugarCleaner::cleanHtml($this->fixSpecialLetters($this->template));
      //save template as file
      $this->saveTemplateAsFile("modules/KReports/Plugins/Integration/kpdfexport/templates/" . $this->id . ".html", $this->template);
   }

   protected function saveTemplateAsFile($file_path, $template) {
      $handle = fopen($file_path, "w+");
      $tmp = html_entity_decode($template);
      fwrite($handle, $tmp);
      fclose($handle);
   }

   protected function fixSpecialLetters($template) {
      return str_replace(array( '&Oacute;', '&oacute;', '&nbsp;', '§' ), array( 'Ó', 'ó', ' ', '&sect;' ), $template);
   }

}
