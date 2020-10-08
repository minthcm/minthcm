<?PHP

/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */
/**
 * THIS CLASS IS FOR DEVELOPERS TO MAKE CUSTOMIZATIONS IN
 */
require_once 'modules/Candidates/Candidates_sugar.php';
require_once 'modules/Candidates/SugarFeeds/CandidatesFeed.php';
require_once __DIR__ . '/../../include/EmailInterface.php';

class Candidates extends Candidates_sugar implements EmailInterface {

   public function save($check_notify = false) {
      $old_bean = $this->fetched_row;
      $beans = array();

      if ( ($old_bean['first_name'] != $this->first_name || $old_bean['last_name'] != $this->last_name ) && $this->load_relationship('candidatures') ) {
         $beans = $this->candidatures->getBeans();
      }

      parent::save($check_notify);

      foreach ( $beans as $b ) {
         $b->generateName();
         $b->save();
      }

      $this->pushFeed();
   }

   public function get_unlinked_email_query($type = array()) {
      return get_unlinked_email_query($type, $this);
   }

   protected function pushFeed() {
      $candidates_feed = new CandidatesFeed();
      $candidates_feed->pushFeed($this, '', array());
   }

}
