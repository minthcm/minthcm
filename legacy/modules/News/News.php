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

require_once('modules/News/SugarFeeds/NewsFeed.php');

class News extends Basic {

   public $new_schema = true;
   public $module_dir = 'News';
   public $object_name = 'News';
   public $table_name = 'news';
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
   public $content_of_announcement;
   public $display_date;
   public $news_type;
   public $news_status;

   public function bean_implements($interface) {
      $result = false;
      if ( $interface === 'ACL' ) {
         $result = true;
      }
      return $result;
   }

   protected function postSave() {
      $nf = new NewsFeed();
      $nf->pushFeed($this, null, null);
   }

   public function save($check_notify = false) {
      if ( $this->news_status == 'published' && $this->content_of_announcement != $this->fetched_row['content_of_announcement'] ) {
         $this->news_status = 'draft';
      }
      return parent::save($check_notify);
   }

   public function mark_deleted($id) {
      $news = BeanFactory::getBean('News', $id);
      if ( $news && !empty($news->id) ) {
         $news->deleteRelatedUsersNews();
      }
      parent::mark_deleted($id);
   }

   protected function deleteRelatedUsersNews() {
      if ( $this->load_relationship('usersnews') ) {
         $related_user_news = BeanFactory::newBean('UsersNews');
         foreach ( $this->usersnews->get() as $related_user_news_id ) {
            $related_user_news->mark_deleted($related_user_news_id);
         }
      }
   }

   public function getRelatedOrganizationalUnitsIDs() {
      global $db;
      $results = array();
      $sql = "SELECT securitygroup_id as id FROM securitygroups_records WHERE record_id = '{$this->id}' AND module='News' AND deleted = 0";
      $result = $db->query($sql);
      while ( $row = $db->fetchByAssoc($result) ) {
         $results[] = $row['id'];
         $organizational_unit = BeanFactory::getBean('SecurityGroups', $row['id']);
         $results = array_merge($results, $organizational_unit->getMemberUnitsIDs());
      }
      return array_unique($results);
   }

   public function canBeDisplayForUser($user_id) {
      $organizational_units_ids = $this->getRelatedOrganizationalUnitsIDs();
      if ( empty($organizational_units_ids) ) {
         return true;
      }
      $organizationalunits_controller = ControllerFactory::getController('SecurityGroups');
      $users_ids = $organizationalunits_controller->getActiveUsers($organizational_units_ids);
      return in_array($user_id, $users_ids);
   }

}
