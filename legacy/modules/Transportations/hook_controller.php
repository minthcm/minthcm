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

class TransportationsLogicHooks {

   public function reformat_number(&$number) {
      $number = number_format($number, 2, '.', ' ');
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

   /**
    * Suma kosztow przejazdow
    */
   public function sum_costs_for_transports($currency_id, $bean) {
      $query = "SELECT SUM(costs.cost_amount) as sum
         FROM costs
         WHERE costs.transportation_id = '" . $bean->id . "'
         AND costs.deleted=0
         AND costs.currency_id='" . $currency_id . "'";
      $result = $bean->db->query($query);
      $row = $bean->db->fetchByAssoc($result);
      return $cost = ($row['sum'] > 0) ? $row['sum'] : "0";
   }

   /**
    * Przejazdy - przed wygenerowaniem pdf
    */
   public function transport_before_pdf(&$bean, $event, $arguments) {
      //find ids of currencies
      $pln_id = $this->retrieveIDByISO('PLN', $bean);
      $eur_id = $this->retrieveIDByISO('EUR', $bean);
      $usd_id = $this->retrieveIDByISO('USD', $bean);

      //sum the pln value (basic currency) and then eur
      $bean->pln_total = $this->sum_costs_for_transports($pln_id, $bean);
      $bean->eur_total = $this->sum_costs_for_transports($eur_id, $bean);
      $bean->usd_total = $this->sum_costs_for_transports($usd_id, $bean);
      $this->reformat_number($bean->pln_total);
      $this->reformat_number($bean->eur_total);
      $this->reformat_number($bean->usd_total);
   }

   public function countTransportationCostsInDelegation($bean, $event, $arguments) {
      if ( $arguments['relationship'] == 'transportations_delegations' && $arguments['related_id'] ) {
         $delegation = BeanFactory::getBean('Delegations', $arguments['related_id']);
         if ( $delegation && $delegation->id ) {
            $delegation->save();
         }
      }
   }

}
