<?php

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
require_once 'modules/Candidatures/Candidatures_sugar.php';
require_once 'modules/Candidatures/SugarFeeds/CandidaturesFeed.php';

class Candidatures extends Candidatures_sugar
{

    public function save($check_notify = false)
    {

      $old_bean = $this->fetched_row;

      if ( $old_bean['status'] != $this->status ) {
         $this->to_decision = 0;
      }

        if (!strlen($this->name) > 0 || $old_bean['recruitment_id'] != $this->recruitment_id || $old_bean['recruitment_end_id'] != $this->recruitment_end_id || $old_bean['parent_id'] != $this->parent_id) {
         $this->generateName();
      }
      $this->change_relation = false;
      if ( $this->recruitment_end_id != $this->fetched_row['recruitment_end_id'] ) {
         $this->change_relation = true;
      }
      $this->setCountEmployees($this->recruitment_end_id, true);
      $this->setCountEmployees($this->fetched_row['recruitment_end_id'], false);

      $this->calculateCurrencies();

      $id = parent::save($check_notify);

      $this->pushFeed();
      
      return $id;
   }

    protected function pushFeed()
    {
      $candidatures_feed = new CandidaturesFeed();
      $candidatures_feed->pushFeed($this, '', array());
   }

    public function generateName()
    {
        $parent_table = $this->getParentTableName();
        $sql = "SELECT CONCAT(COALESCE(first_name, ''), IF(first_name IS NULL, '', ' '), last_name)
            FROM {$parent_table}
            WHERE id = '{$this->parent_id}'
        ";
        $candidate_name = $this->db->getOne($sql);

        $recruitement_id = $this->recruitment_end_id ?? $this->recruitment_id;
        $sql = "SELECT p.name
            FROM positions p
            WHERE p.id = (
                SELECT r.position_id
                FROM recruitments r
                where r.id = '{$recruitement_id}'
            )
        ";
        $position_name = $this->db->getOne($sql);

        $this->name = "{$candidate_name} {$position_name}";
   }

    private function setCountEmployees($recruitment_end_id, $change_rel = true)
    {
        if ('' != $recruitment_end_id) {
         $recruitment = BeanFactory::getBean('Recruitments', $recruitment_end_id);
         if ( $recruitment->load_relationship('candidatures_end') ) {
            $employees_number = $this->countEmployees($recruitment, $change_rel);
            if ( $employees_number != $recruitment->employees_number ) {
               $recruitment->counted = true;
               $recruitment->employees_number = $employees_number;
               $recruitment->save();
            }
         }
      }
   }

    private function countEmployees($recruitment, $change_rel = true)
    {
      $result = 0;
      $candidatures = $recruitment->candidatures_end->getBeans();
      if ( $this->change_relation ) {
         if ( $change_rel ) {
            $candidatures[] = $this;
         } else {
            unset($candidatures[$this->id]);
         }
      }
      foreach ( $candidatures as $candidature ) {
            if ('Hired' == $candidature->status) {
            $result++;
         }
      }
      return $result;
   }

    protected function calculateCurrencies()
    {
      $currency = new Currency();
      $currency->retrieve($this->currency_id);
      if ( isset($this->dg_amount) ) {
         $this->dg_amount = !number_empty($this->dg_amount) ? $this->dg_amount : 0.0;
         $this->dg_amount_usdollar = $currency->convertToDollar(unformat_number($this->dg_amount));
      }
      if ( isset($this->net_amount) ) {
         $this->net_amount = !number_empty($this->net_amount) ? $this->net_amount : 0.0;
         $this->net_amount_usdollar = $currency->convertToDollar(unformat_number($this->net_amount));
      }
      if ( isset($this->gross_amount) ) {
         $this->gross_amount = !number_empty($this->gross_amount) ? $this->gross_amount : 0.0;
         $this->gross_amount_usdollar = $currency->convertToDollar(unformat_number($this->gross_amount));
      }
      if ( isset($this->salary_net) ) {
         $this->salary_net = !number_empty($this->salary_net) ? $this->salary_net : 0.0;
         $this->salary_net_usdollar = $currency->convertToDollar(unformat_number($this->salary_net));
      }
   }

    protected function getParentTableName() {
        if ($this->parent_type === 'Employees') {
            return 'users';
        } else if ($this->parent_type === 'Candidates') {
            return 'candidates';
}
    }
}
