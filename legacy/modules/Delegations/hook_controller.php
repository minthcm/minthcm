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
 * Copyright (C) 2018-2024 MintHCM
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

#[\AllowDynamicProperties]
class DelegationsLogicHooks {

   public function reformat_number(&$number) {
      $number = number_format(intval($number), 2, '.', ' ');
   }

   public function delegations_before_pdf(&$bean, $event, $arguments) {
      $currency = new Currency();
      $currency->retrieve($bean->currency_id);

      $query = "SELECT * FROM transportations
         WHERE transportations.delegation_id =  '" . $bean->id . "'
         AND transportations.deleted = 0
         ORDER BY transportations.trans_date ASC";
      $result = $bean->db->query($query);

      $cities = array(); //define destinations
      $tmeans = array(); //define means of transport

      while ( ($row = $bean->db->fetchByAssoc($result) ) != null ) {
         array_push($cities, trim($row['from_city']));
         array_push($cities, trim($row['to_city']));
         if ( $row['type'] == "^train^" || $row['type'] == "^car^" )
            array_push($tmeans, translate("transport_type", "Transportations", str_replace("^", "", $row['type'])));
         else if ( $row['type'] == "^other^" )
            array_push($tmeans, $row['other_transportation']);
      }

      $cities = array_unique($cities);
      $tmeans = array_unique($tmeans);


      $bean->from_city = array_shift($cities);
      $bean->destinations = implode(",<br/>", $cities);
      $bean->means_of_transport = implode(", ", $tmeans);
      global $timedate, $current_user;
      $date_format = $timedate->get_date_format($current_user);
      $time_format = $timedate->get_time_format($current_user);
      $date_entered = getDateTimeObject($bean->date_entered);
      $day_start = getDateTimeObject($bean->start_date);
      $day_end = getDateTimeObject($bean->end_date);
      $bean->day_entered = ($date_entered) ? $date_entered->format($date_format) : '';
      $bean->day_start = ($day_start) ? $day_start->format($date_format) : '';
      $bean->day_end = ($day_end) ? $day_end->format($date_format) : '';
      $bean->hour_start = ($day_start) ? $date_entered->format($time_format) : '';
      $bean->hour_end = ($day_end) ? $date_entered->format($time_format) : '';

      $query = "SELECT SUM(costs.cost_amount) as sum
         FROM costs
         WHERE costs.delegation_id = '" . $bean->id . "'
         AND costs.deleted=0
         AND costs.type = 'transport'
         AND costs.currency_id='-99'";
      $result = $bean->db->query($query);
      $row = $bean->db->fetchByAssoc($result);
      $bean->transport_cost = (float) ($row['sum'] > 0) ? $row['sum'] : "0.00";

      //transport_cost_eur
      $query = "SELECT SUM(costs.cost_amount) as sum
         FROM costs
         WHERE costs.delegation_id = '" . $bean->id . "'
         AND costs.deleted=0
         AND costs.type = 'transport'
         AND costs.currency_id  in(SELECT id FROM currencies WHERE iso4217='EUR' AND deleted=0)";
      $result = $bean->db->query($query);
      $row = $bean->db->fetchByAssoc($result);
      $bean->transport_cost_eur = (float) ($row['sum'] > 0) ? $row['sum'] : "0.00";

      //transport_cost_usd
      $query = "SELECT SUM(costs.cost_amount) as sum
         FROM costs
         WHERE costs.delegation_id = '" . $bean->id . "'
         AND costs.deleted=0
         AND costs.type = 'transport'
         AND costs.currency_id  in(SELECT id FROM currencies WHERE iso4217='USD' AND deleted=0)";
      $result = $bean->db->query($query);
      $row = $bean->db->fetchByAssoc($result);
      $bean->transport_cost_usd = (float) ($row['sum'] > 0) ? $row['sum'] : "0.00";

      //accommodation
      $query = "SELECT SUM(costs.cost_amount) as sum
         FROM costs
         WHERE costs.delegation_id = '" . $bean->id . "'
         AND costs.type = 'accommodation'
         AND costs.deleted=0
         AND costs.currency_id='-99'";
      $result = $bean->db->query($query);
      $row = $bean->db->fetchByAssoc($result);
      $bean->total_accommodation = (float) ($row['sum'] > 0) ? $row['sum'] : "0.00";

      //accommodation_eur
      $query = "SELECT SUM(costs.cost_amount) as sum
         FROM costs
         WHERE costs.delegation_id = '" . $bean->id . "'
         AND costs.type = 'accommodation'
         AND costs.deleted=0
         AND costs.currency_id in(SELECT id FROM currencies WHERE iso4217='EUR' AND deleted=0)";
      $result = $bean->db->query($query);
      $row = $bean->db->fetchByAssoc($result);
      $bean->total_accommodation_eur = (float) ($row['sum'] > 0) ? $row['sum'] : "0.00";

      //accommodation_usd
      $query = "SELECT SUM(costs.cost_amount) as sum
         FROM costs
         WHERE costs.delegation_id = '" . $bean->id . "'
         AND costs.type = 'accommodation'
         AND costs.deleted=0
         AND costs.currency_id in(SELECT id FROM currencies WHERE iso4217='USD' AND deleted=0)";
      $result = $bean->db->query($query);
      $row = $bean->db->fetchByAssoc($result);
      $bean->total_accommodation_usd = (float) ($row['sum'] > 0) ? $row['sum'] : "0.00";

      //other
      $query = "SELECT SUM(costs.cost_amount) as sum
         FROM costs
         WHERE costs.delegation_id = '" . $bean->id . "'
         AND (costs.type = 'restaurant' OR costs.type = 'other')
         AND costs.deleted=0
         AND costs.currency_id='-99'";
      $result = $bean->db->query($query);
      $row = $bean->db->fetchByAssoc($result);
      $bean->other = (float) ($row['sum'] > 0) ? $row['sum'] : "0.00";

      //other_eur
      $query = "SELECT SUM(costs.cost_amount) as sum
         FROM costs
         WHERE costs.delegation_id = '" . $bean->id . "'
         AND (costs.type = 'restaurant' OR costs.type = 'other')
         AND costs.deleted=0
         AND costs.currency_id in(SELECT id FROM currencies WHERE iso4217='EUR' AND deleted=0)";
      $result = $bean->db->query($query);
      $row = $bean->db->fetchByAssoc($result);
      $bean->other_eur = (float) ($row['sum'] > 0) ? $row['sum'] : "0.00";

      //other_usd
      $query = "SELECT SUM(costs.cost_amount) as sum
         FROM costs
         WHERE costs.delegation_id = '" . $bean->id . "'
         AND (costs.type = 'restaurant' OR costs.type = 'other')
         AND costs.deleted=0
         AND costs.currency_id in(SELECT id FROM currencies WHERE iso4217='USD' AND deleted=0)";
      $result = $bean->db->query($query);
      $row = $bean->db->fetchByAssoc($result);
      $bean->other_usd = (float) ($row['sum'] > 0) ? $row['sum'] : "0.00";
      
      $query = "SELECT COUNT( * ) as c
         FROM costs
         WHERE costs.delegation_id = '" . $bean->id . "'
         AND costs.type = 'restaurant'
         AND costs.deleted=0 
         AND costs.currency_id in(SELECT id FROM currencies WHERE iso4217='EUR' AND deleted=0)";

      $row = $bean->db->fetchByAssoc($bean->db->query($query));
      $bean->restaurant_bills_eur = (float) $row['c'];

      $query = "SELECT COUNT( * ) as c
         FROM costs
         WHERE costs.delegation_id = '" . $bean->id . "'
         AND costs.type = 'restaurant'
         AND costs.deleted=0
         AND costs.currency_id in(SELECT id FROM currencies WHERE iso4217='USD' AND deleted=0)";

      $row = $bean->db->fetchByAssoc($bean->db->query($query));
      $bean->restaurant_bills_usd = (float) $row['c'];
      $date1 = getDateTimeObject($bean->start_date);
      $date2 = getDateTimeObject($bean->end_date);
      if ($date1 && $date2) {
          $period = $date1->diff($date2);
    
          $bean->regiments_eur = (($period->d + 1) - $bean->restaurant_bills_eur / 3) * $bean->regimen_value;
          $bean->regiments_usd = (($period->d + 1) - $bean->restaurant_bills_usd / 3) * $bean->regimen_value;
      }

      ///////////
      $bean->total_expenses = (float) $bean->other + (float) $bean->accommodation_lump_sum + (float) $bean->total_accommodation + (float) $bean->regiments + (float) $bean->transport_cost;
      $bean->total_expenses_eur = (float) $bean->other_eur + (float) $bean->accommodation_lump_sum_eur + (float) $bean->total_accommodation_eur + (float) $bean->regiments_eur + (float) $bean->transport_cost_eur;
      $bean->total_expenses_usd = (float) $bean->other_usd + (float) $bean->accommodation_lump_sum_usd + (float) $bean->total_accommodation_usd + (float) $bean->regiments_usd + (float) $bean->transport_cost_usd;

      $bean->payoff_sum = ($bean->total_expenses - (float) $bean->obtained_sum) > 0 ? (float) $bean->total_expenses - (float) $bean->obtained_sum : 0;
      $bean->payoff_sum_eur = ($bean->total_expenses_eur - (float) $bean->obtained_sum_eur) > 0 ? ($bean->total_expenses_eur - (float) $bean->obtained_sum_eur) : 0;
      $bean->payoff_sum_usd = ($bean->total_expenses_usd - (float) $bean->obtained_sum_usd) > 0 ? ($bean->total_expenses_usd - (float) $bean->obtained_sum_usd) : 0;

      $bean->return_sum = ($bean->total_expenses - (float) $bean->obtained_sum) < 0 ? ($bean->obtained_sum - (float) $bean->total_expenses) : 0;
      $bean->return_sum_eur = ($bean->total_expenses_eur - (float) $bean->obtained_sum_eur) < 0 ? ($bean->obtained_sum_eur - (float) $bean->total_expenses_eur) : 0;
      $bean->return_sum_usd = ($bean->total_expenses_usd - (float) $bean->obtained_sum_usd) < 0 ? ($bean->obtained_sum_usd - (float) $bean->total_expenses_usd) : 0;

      $values = array(
         'payoff_sum_eur',
         'return_sum_eur',
         'total_expenses_eur',
         'transport_cost_eur',
         'total_accommodation',
         'total_accommodation_eur',
         'other',
         'other_eur',
         'regiments_eur',
         'accommodation_lump_sum_eur',
         'obtained_sum_eur',
      );
      foreach ( $values as $key => &$number ) {
         $this->reformat_number($bean->$number);
      }
      $bean->end_date_plus_one = date($timedate->get_date_format($current_user), strtotime("+1 days", strtotime($bean->end_date)));
   }

   public function retrieveIDByISO($iso, $bean) {
      $currency = new Currency();
      $defiso = $currency->getDefaultISO4217();

      if ( $defiso == $iso )
         return '-99';

      $query = "SELECT id FROM currencies WHERE iso4217='$iso' AND deleted=0 AND status='Active';";
      $result = $bean->db->query($query);
      if ( $result ) {
         $row = $bean->db->fetchByAssoc($result);
         if ( $row ) {
            return $row['id'];
         }
      }
      return '';
   }

}
