<?PHP

/* * *******************************************************************************
 * SugarCRM is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2011 SugarCRM Inc.
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
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
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
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 * ****************************************************************************** */

/**
 * THIS CLASS IS FOR DEVELOPERS TO MAKE CUSTOMIZATIONS IN
 */
require_once('modules/Transportations/Transportations_sugar.php');
require_once('modules/Transportations/SugarFeeds/TransportationsFeed.php');

class Transportations extends Transportations_sugar {

   public function save($check_notify = false) {
      $this->generateTransportationName();
      $this->countTotalCost();
      
      $result = parent::save($check_notify);

      $transportations_feed = new TransportationsFeed();
      $transportations_feed->pushFeed($this, null, null);

      if ( !empty($this->delegation_id) ) {
         $delegation = BeanFactory::getBean('Delegations', $this->delegation_id);
         $delegation->save();
      }
      return $result;
   }

   public function generateTransportationName() {
      global $timedate;
      $_date = date('Y-m-d', strtotime($this->trans_date));
      $this->name = sprintf("%s %s %s", translate('LBL_TITLE', 'Transportations'), $_date, $this->from_city);
   }

   public function countTotalCost() {
      if ( empty($this->id) ) {
         $this->cost_total = 0;
         return;
      }
      $query = "SELECT SUM(costs.cost_amount_usdollars) as sum
         FROM costs
         WHERE costs.transportation_id = '" . $this->id . "'
         AND costs.deleted=0";
      $result = $this->db->query($query);
      $row = $this->db->fetchByAssoc($result);
      $this->cost_total = $row['sum'];
      if ( is_null($row['sum']) ) {
         $this->cost_total = 0;
      }
   }

}
