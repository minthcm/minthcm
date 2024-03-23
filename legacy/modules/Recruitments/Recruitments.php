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

require_once 'modules/Recruitments/Recruitments_sugar.php';
require_once 'modules/Recruitments/SugarFeeds/RecruitmentsFeed.php';

class Recruitments extends Recruitments_sugar {

   public $counted = false;

   public function save($check_notify = false) {

      if ( $this->recruitment_type == 'continuous' ) {
         $this->vacancy = '';
      }

      if ( !$this->counted ) {
         $this->load_relationship('candidatures_end');
         $employees_number = 0;
         $candidatures = $this->candidatures_end->getBeans();
         foreach ( $candidatures as $candidature ) {
            if ( $candidature->status == 'Hired' ) {
               $employees_number++;
            }
         }
         $this->employees_number = $employees_number;
      }

      $old_bean = $this->fetched_row;
      $beans = array();

      $this->name = $this->position_name . ' ' . $this->start_date;
      $curr_name = $this->name;
      $old_bean_name = $old_bean['name'];

      $this->calculateCurrencies();

      parent::save($check_notify);

      //name is a calculated field so it could be change due to change of the position name after save
      if ( ($curr_name != $this->name || $old_bean_name != $this->name) && $this->load_relationship('candidatures') ) {
         $beans = $this->candidatures->getBeans();
      }

      foreach ( $beans as $b ) {
         $b->generateName();
         $b->save();
      }

      $this->pushFeed();
   }

   protected function pushFeed() {
      $recruitments_feed = new RecruitmentsFeed();
      $recruitments_feed->pushFeed($this, '', array());
   }

   protected function calculateCurrencies() {
      $currency = new Currency();
      $currency->retrieve($this->currency_id);
      if ( isset($this->salary_from) ) {
         $this->salary_from = !number_empty($this->salary_from) ? $this->salary_from : 0.0;
         $this->salary_from_usdollar = $currency->convertToDollar(unformat_number($this->salary_from));
      }
      if ( isset($this->salary_to) ) {
         $this->salary_to = !number_empty($this->salary_to) ? $this->salary_to : 0.0;
         $this->salary_to_usdollar = $currency->convertToDollar(unformat_number($this->salary_to));
      }
   }

}
