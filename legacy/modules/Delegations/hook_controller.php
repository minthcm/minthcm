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

class DelegationsLogicHooks {

   public function reformat_number(&$number) {
      $number = number_format($number, 2, '.', ' ');
   }

   public function delegations_before_pdf(&$bean, $event, $arguments) {
      $currency = new Currency();
      $currency->retrieve($bean->currency_id);
      $pln_id = $this->retrieveIDByISO('PLN', $bean);
      $eur_id = $this->retrieveIDByISO('EUR', $bean);

      //Pobranie unikalnych miast i typow transportu
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

      // Uzupełnienie pól

      $bean->from_city = array_shift($cities);
      $bean->destinations = implode(",<br/>", $cities);
      $bean->means_of_transport = implode(", ", $tmeans);
      global $timedate, $current_user;
      $bean->day_entered = $timedate->swap_formats($bean->date_entered, $timedate->get_date_time_format($current_user), $timedate->get_date_format($current_user));
      $bean->day_start = $timedate->swap_formats($bean->start_date, $timedate->get_date_time_format($current_user), $timedate->get_date_format($current_user));
      $bean->day_end = $timedate->swap_formats($bean->end_date, $timedate->get_date_time_format($current_user), $timedate->get_date_format($current_user));
      $bean->hour_start = $timedate->swap_formats($bean->start_date, $timedate->get_date_time_format($current_user), $timedate->get_time_format($current_user));
      $bean->hour_end = $timedate->swap_formats($bean->end_date, $timedate->get_date_time_format($current_user), $timedate->get_time_format($current_user));

      ///////// Całkowity koszt transportu z zł
      //transport_cost //pln
      $query = "SELECT SUM(costs.cost_amount) as sum
         FROM costs
         WHERE costs.delegation_id = '" . $bean->id . "'
         AND costs.deleted=0
         AND costs.type = 'transport'
         AND costs.currency_id='-99'";
      $result = $bean->db->query($query);
      $row = $bean->db->fetchByAssoc($result);
      $bean->transport_cost = ($row['sum'] > 0) ? $row['sum'] : "0.00";

      //Całkowity koszt transportu w euro
      //transport_cost_eur
      $query = "SELECT SUM(costs.cost_amount) as sum
         FROM costs
         WHERE costs.delegation_id = '" . $bean->id . "'
         AND costs.deleted=0
         AND costs.type = 'transport'
         AND costs.currency_id  in(SELECT id FROM currencies WHERE iso4217='EUR' AND deleted=0)";
      $result = $bean->db->query($query);
      $row = $bean->db->fetchByAssoc($result);
      $bean->transport_cost_eur = ($row['sum'] > 0) ? $row['sum'] : "0.00";

      //Całkowity koszt transportu w dolarach
      //transport_cost_usd
      $query = "SELECT SUM(costs.cost_amount) as sum
         FROM costs
         WHERE costs.delegation_id = '" . $bean->id . "'
         AND costs.deleted=0
         AND costs.type = 'transport'
         AND costs.currency_id  in(SELECT id FROM currencies WHERE iso4217='USD' AND deleted=0)";
      $result = $bean->db->query($query);
      $row = $bean->db->fetchByAssoc($result);
      $bean->transport_cost_usd = ($row['sum'] > 0) ? $row['sum'] : "0.00";

      //Suma kosztow noclegow w zł
      //accommodation (pln)
      $query = "SELECT SUM(costs.cost_amount) as sum
         FROM costs
         WHERE costs.delegation_id = '" . $bean->id . "'
         AND costs.type = 'accommodation'
         AND costs.deleted=0
         AND costs.currency_id='-99'";
      $result = $bean->db->query($query);
      $row = $bean->db->fetchByAssoc($result);
      $bean->total_accommodation = ($row['sum'] > 0) ? $row['sum'] : "0.00";

      //Suma kosztów noclegów w euro	
      //accommodation_eur
      $query = "SELECT SUM(costs.cost_amount) as sum
         FROM costs
         WHERE costs.delegation_id = '" . $bean->id . "'
         AND costs.type = 'accommodation'
         AND costs.deleted=0
         AND costs.currency_id in(SELECT id FROM currencies WHERE iso4217='EUR' AND deleted=0)";
      $result = $bean->db->query($query);
      $row = $bean->db->fetchByAssoc($result);
      $bean->total_accommodation_eur = ($row['sum'] > 0) ? $row['sum'] : "0.00";


      //Suma kosztów noclegów w dolarach
      //accommodation_usd
      $query = "SELECT SUM(costs.cost_amount) as sum
         FROM costs
         WHERE costs.delegation_id = '" . $bean->id . "'
         AND costs.type = 'accommodation'
         AND costs.deleted=0
         AND costs.currency_id in(SELECT id FROM currencies WHERE iso4217='USD' AND deleted=0)";
      $result = $bean->db->query($query);
      $row = $bean->db->fetchByAssoc($result);
      $bean->total_accommodation_usd = ($row['sum'] > 0) ? $row['sum'] : "0.00";


      //Suma innych kosztów w zł
      //other (pln)
      $query = "SELECT SUM(costs.cost_amount) as sum
         FROM costs
         WHERE costs.delegation_id = '" . $bean->id . "'
         AND (costs.type = 'restaurant' OR costs.type = 'other')
         AND costs.deleted=0
         AND costs.currency_id='-99'";
      $result = $bean->db->query($query);
      $row = $bean->db->fetchByAssoc($result);
      $bean->other = ($row['sum'] > 0) ? $row['sum'] : "0.00";

      //Suma innych kosztów w euro	
      //other_eur
      $query = "SELECT SUM(costs.cost_amount) as sum
         FROM costs
         WHERE costs.delegation_id = '" . $bean->id . "'
         AND (costs.type = 'restaurant' OR costs.type = 'other')
         AND costs.deleted=0
         AND costs.currency_id in(SELECT id FROM currencies WHERE iso4217='EUR' AND deleted=0)";
      $result = $bean->db->query($query);
      $row = $bean->db->fetchByAssoc($result);
      $bean->other_eur = ($row['sum'] > 0) ? $row['sum'] : "0.00";

      //Suma innych kosztów w dolarach
      //other_usd
      $query = "SELECT SUM(costs.cost_amount) as sum
         FROM costs
         WHERE costs.delegation_id = '" . $bean->id . "'
         AND (costs.type = 'restaurant' OR costs.type = 'other')
         AND costs.deleted=0
         AND costs.currency_id in(SELECT id FROM currencies WHERE iso4217='USD' AND deleted=0)";
      $result = $bean->db->query($query);
      $row = $bean->db->fetchByAssoc($result);
      $bean->other_usd = ($row['sum'] > 0) ? $row['sum'] : "0.00";
      
           
      //Liczba rachunkow za restauracje (eur)
      $query = "SELECT COUNT( * ) as c
         FROM costs
         WHERE costs.delegation_id = '" . $bean->id . "'
         AND costs.type = 'restaurant'
         AND costs.deleted=0 
         AND costs.currency_id in(SELECT id FROM currencies WHERE iso4217='EUR' AND deleted=0)";

      $row = $bean->db->fetchByAssoc($bean->db->query($query));
      $bean->restaurant_bills_eur = $row['c'];

      //Liczba rachunkow za restauracje (usd)
      $query = "SELECT COUNT( * ) as c
         FROM costs
         WHERE costs.delegation_id = '" . $bean->id . "'
         AND costs.type = 'restaurant'
         AND costs.deleted=0
         AND costs.currency_id in(SELECT id FROM currencies WHERE iso4217='USD' AND deleted=0)";

      $row = $bean->db->fetchByAssoc($bean->db->query($query));
      $bean->restaurant_bills_usd = $row['c'];

      // Konwersja dat i dlugosci pobytu
      $date1 = new DateTime($bean->start_date);
      $date2 = new DateTime($bean->end_date);
      $period = $date1->diff($date2);

      $bean->regiments_eur = (($period->d + 1) - $bean->restaurant_bills_eur / 3) * $bean->regimen_value;
      $bean->regiments_usd = (($period->d + 1) - $bean->restaurant_bills_usd / 3) * $bean->regimen_value;

      //depending on delegation default currency	
      if ( $currency->iso4217 == 'PLN' ) {
         $bean->regiments_eur = "0.00";
         $bean->accommodation_lump_sum_eur = "0.00";
         $bean->obtained_sum_eur = "0.00";
         $bean->regiments_usd = "0.00";
         $bean->accommodation_lump_sum_usd = "0.00";
         $bean->obtained_sum_usd = "0.00";
      } elseif ( $currency->iso4217 == 'EUR' ) {
         $bean->regiments = "0.00";
         $bean->regiments_usd = "0.00";

         $bean->accommodation_lump_sum_eur = $bean->accommodation_lump_sum;
         $bean->accommodation_lump_sum_usd = "0.00";
         $bean->accommodation_lump_sum = "0.00";

         $bean->obtained_sum_eur = $bean->obtained_sum;
         $bean->obtained_sum_usd = "0.00";
         $bean->obtained_sum = "0.00";
      } elseif ( $currency->iso4217 == 'USD' ) {
         $bean->regiments_eur = "0.00";
         $bean->regiments = "0.00";

         $bean->accommodation_lump_sum_usd = $bean->accommodation_lump_sum;
         $bean->accommodation_lump_sum_eur = "0.00";
         $bean->accommodation_lump_sum = "0.00";

         $bean->obtained_sum_usd = $bean->obtained_sum;
         $bean->obtained_sum_eur = "0.00";
         $bean->obtained_sum = "0.00";
      }
      //Wyliczenie całkowitych kosztów
      ///////////
      $bean->total_expenses = $bean->other + $bean->accommodation_lump_sum + $bean->total_accommodation + $bean->regiments + $bean->transport_cost;
      $bean->total_expenses_eur = $bean->other_eur + $bean->accommodation_lump_sum_eur + $bean->total_accommodation_eur + $bean->regiments_eur + $bean->transport_cost_eur;
      $bean->total_expenses_usd = $bean->other_usd + $bean->accommodation_lump_sum_usd + $bean->total_accommodation_usd + $bean->regiments_usd + $bean->transport_cost_usd;

      $bean->payoff_sum = ($bean->total_expenses - $bean->obtained_sum) > 0 ? $bean->total_expenses - $bean->obtained_sum : "0";
      $bean->payoff_sum_eur = ($bean->total_expenses_eur - $bean->obtained_sum_eur) > 0 ? ($bean->total_expenses_eur - $bean->obtained_sum_eur) : "0";
      $bean->payoff_sum_usd = ($bean->total_expenses_usd - $bean->obtained_sum_usd) > 0 ? ($bean->total_expenses_usd - $bean->obtained_sum_usd) : "0";

      $bean->return_sum = ($bean->total_expenses - $bean->obtained_sum) < 0 ? ($bean->obtained_sum - $bean->total_expenses) : "0";
      $bean->return_sum_eur = ($bean->total_expenses_eur - $bean->obtained_sum_eur) < 0 ? ($bean->obtained_sum_eur - $bean->total_expenses_eur) : "0";
      $bean->return_sum_usd = ($bean->total_expenses_usd - $bean->obtained_sum_usd) < 0 ? ($bean->obtained_sum_usd - $bean->total_expenses_usd) : "0";

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
         $this->reformat_number($bean->$values[$key]);
      }
      $bean->end_date_plus_one = date($timedate->get_date_format($current_user), strtotime("+1 days", strtotime($bean->end_date)));
   }

   /**
    * Pobranie id waluty po identyfikatorze ISO
    */
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
