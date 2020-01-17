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
require_once('modules/Delegations/Delegations_sugar.php');
require_once('include/SequenceUtils.php');
require_once('modules/Delegations/SugarFeeds/DelegationsFeed.php');

class Delegations extends Delegations_sugar
{

    public function save($check_notify = false)
    {
        $this->getDelegationLocaleValues();

        if ($this->delegation_id == "") {
            $this->generateDelegationID();
        }

        $this->countDelegationFields();

        $this->convertCurrencyFields();

        if ($this->exchange_rate && $this->exchange_rate != $this->fetched_row['exchange_rate']) {
            $this->countRelatedCostsAmounts();
        }

        $result = parent::save($check_notify);

        $delegations_feed = new DelegationsFeed();
        $delegations_feed->pushFeed($this, null, null);

        return $result;
    }

    public function mark_deleted($id)
    {
        $this->decrementSequenceValue();
        parent::mark_deleted($id);
    }

    public function countDelegationFields()
    {
        $this->countTransportCost();
        $this->countRestaurantBills();
        $this->countRegiments();
        $this->countAccomodations();
        $this->countTotalAccommodation();

        $number_of_nights = $this->countNumberOfNights();
        if ($number_of_nights > 0 && $this->owner == 0) {
            $this->countAccommodationLumpSum($number_of_nights);
        }

        $this->countOtherCosts();
        $this->countTotalExpenses();
        $this->countPayoffSum();
        $this->countReturnSum();
        $this->countCostsInForeignCurrency();
    }

    public function countCostsInForeignCurrency()
    {
        $this->costs_sum = 0;
        $this->load_relationship('costs');
        $related_costs   = $this->costs->getBeans();
        foreach ($related_costs as $cost) {
            if ($cost->currency_id != '-99') {
                $this->costs_sum += $cost->cost_amount;
            }
        }
    }

    protected function getExchangeRate()
    {
        $exchange_rate = $this->exchange_rate;
        if ($this->exchange_rate == 0) {
            $exchange_rate = 1;
        }
        return $exchange_rate;
    }

    public function convertCurrencyFields()
    {
        $exchange_rate                         = $this->getExchangeRate();
        $this->regiments_usdollar              = $this->regiments * $exchange_rate;
        $this->accommodation_lump_sum_usdollar = $this->accommodation_lump_sum * $exchange_rate;
//        $this->transport_cost_usdollar         = $this->transport_cost * $exchange_rate;
//      $this->total_accommodation_usdollar = $this->total_accommodation * $exchange_rate;
//        $this->other_usdollar                  = $this->other * $exchange_rate;
        $this->total_expenses_usdollar         = $this->total_expenses * $exchange_rate;
        $this->obtained_sum_usdollar           = $this->obtained_sum * $$exchange_rate;
        $this->payoff_sum_usdollar             = $this->payoff_sum * $exchange_rate;
        $this->return_sum_usdollar             = $this->return_sum * $exchange_rate;
        $this->regimen_value_usdollar          = $this->regimen_value * $exchange_rate;
        $this->accommodation_value_usdollar    = $this->accommodation_value * $exchange_rate;
        $this->obtained_sum_usdollars          = $this->obtained_sum * $exchange_rate;
    }

    public function generateSequenceName()
    {
        global $timedate;
        $del_year  = $timedate->swap_formats($this->start_date, $timedate::DB_DATETIME_FORMAT, "Y");
        $del_month = $timedate->swap_formats($this->start_date, $timedate::DB_DATETIME_FORMAT, "m");
        return "delegations_".$this->assigned_user_name."_".$del_month."_".$del_year;
    }

    public function generateDelegationID()
    {
        global $timedate;
        $del_year      = $timedate->swap_formats($this->start_date, $timedate::DB_DATETIME_FORMAT, "Y");
        $del_month     = $timedate->swap_formats($this->start_date, $timedate::DB_DATETIME_FORMAT, "m");
        $assigned_user = BeanFactory::getBean('Users', $this->assigned_user_id);
        if ($assigned_user && $assigned_user->id) {
            $seq                 = new SequenceUtils();
            $seq_name            = $this->generateSequenceName();
            $this->delegation_id = sprintf("%s/%02d/%02d/%d", $assigned_user->user_name, $del_year, $del_month,
                $seq->getNext($seq_name));
            $this->name          = translate('LBL_TITLE', 'Delegations').$this->delegation_id;
        }
    }

    public function countRelatedCostsAmounts()
    {
        $this->load_relationship('costs');
        $related_costs = $this->costs->getBeans();
        foreach ($related_costs as $cost) {
            $cost->save();
        }
    }

    public function getDelegationLocaleValues()
    {
        $locale = BeanFactory::getBean('DelegationsLocale', $this->delegation_locale_id);
        if ($locale && $locale->id) {
            $this->accommodation_value = $locale->accommodation_value;
            $this->regimen_value       = $locale->regimen_value;
        }
    }

    public function decrementSequenceValue()
    {
        global $timedate, $current_user;
        $del_year         = $timedate->swap_formats($this->start_date, $timedate->get_date_time_format($current_user),
            "Y");
        $del_month        = $timedate->swap_formats($this->start_date, $timedate->get_date_time_format($current_user),
            "m");
        $this->start_date = $timedate->swap_formats($this->start_date, $timedate->get_date_time_format($current_user),
            $timedate::DB_DATETIME_FORMAT);
        $assigned_user    = BeanFactory::getBean('Users', $this->assigned_user_id);
        if ($assigned_user && $assigned_user->id) {
            $seq                = new SequenceUtils();
            $seq_name           = $this->generateSequenceName();
            $last_delegation_id = sprintf("%s/%02d/%02d/%d", $assigned_user->user_name, $del_year, $del_month,
                $seq->getCurrent($seq_name));
            if ($this->delegation_id == $last_delegation_id) {
                $seq->decrement($seq_name);
            }
        }
    }

    public function countTransportCost()
    {
        if (empty($this->id)) {
            $this->transport_cost = 0;
            return;
        }
        $query                         = "SELECT SUM(c.cost_amount_usdollars) sum FROM costs c
         JOIN transportations t ON c.transportation_id=t.id AND t.delegation_id='".$this->id."' AND t.deleted=0
         WHERE c.deleted=0";
        $result                        = $this->db->query($query);
        $row                           = $this->db->fetchByAssoc($result);
        $this->transport_cost_usdollar = ( $row['sum'] > 0 ) ? $row['sum'] : 0;
        $this->transport_cost          = $this->transport_cost_usdollar / $this->getExchangeRate();
    }

    public function countRestaurantBills()
    {
        if (empty($this->id)) {
            $this->restaurant_bills = 0;
            return;
        }
        $query                  = "SELECT COUNT( * ) as c
         FROM costs
         WHERE costs.delegation_id = '".$this->id."'
         AND costs.type = 'restaurant'
         AND costs.deleted=0
         AND costs.currency_id='-99'";
        $row                    = $this->db->fetchByAssoc($this->db->query($query));
        $this->restaurant_bills = $row['c'];
    }

    public function countRegiments()
    {
        $date1  = new DateTime($this->start_date);
        $date2  = new DateTime($this->end_date);
        $period = $date1->diff($date2);

        $regiment = 0;
        if ($period->d > 0) {
            $regiment += $this->regimen_value * $period->d;
            if ($period->h >= 8) {
                $regiment += $this->regimen_value;
            } else if ($period->h > 0) {
                $regiment += $this->regimen_value / 2;
            }
        } else if ($period->h >= 12) {
            $regiment = $this->regimen_value;
        } else if ($period->h >= 8) {
            $regiment = $this->regimen_value / 2;
        }

        $regiment -= $this->assured_number_of_breakfasts * $this->regimen_value / 4;
        $regiment -= $this->assured_number_of_dinners * $this->regimen_value / 2;
        $regiment -= $this->assured_number_of_suppers * $this->regimen_value / 4;

        $paid_meals = $this->getMealsWithLoggedCosts();
        $regiment   -= $paid_meals['breakfast'] * $this->regimen_value / 4;
        $regiment   -= $paid_meals['dinner'] * $this->regimen_value / 2;
        $regiment   -= $paid_meals['supper'] * $this->regimen_value / 4;

        $this->regiments = ( $regiment > 0 ) ? $regiment : 0;
    }

    public function getMealsWithLoggedCosts()
    {
        $result     = array();
        $meal_types = array('breakfast', 'dinner', 'supper');
        foreach ($meal_types as $type) {
            $result[$type] = $this->countMealsOfSingleType($type);
        }
        return $result;
    }

    public function countMealsOfSingleType($meal_type)
    {
        if (empty($this->id)) {
            return 0;
        }
        $query = "SELECT COUNT( * ) as c
         FROM costs
         WHERE costs.delegation_id = '".$this->id."'
         AND costs.type = 'restaurant'
         AND costs.type_of_meal = '".$meal_type."'
         AND costs.deleted=0";
        return $this->db->getOne($query);
    }

    public function countAccomodations()
    {
        if (empty($this->id)) {
            $this->accomodations = 0;
            return;
        }
        $query               = "SELECT SUM(costs.accommodation_no) as c
         FROM costs
         WHERE costs.delegation_id = '".$this->id."'
         AND costs.type = 'accommodation'
         AND costs.deleted=0";
        $result              = $this->db->query($query);
        $row                 = $this->db->fetchByAssoc($result);
        $this->accomodations = $row['c'];
    }

    public function countTotalAccommodation()
    {
        if (empty($this->id)) {
            $this->total_accommodation           = 0;
            $this->total_accommodation_usdollar = 0;
            return;
        }
        $query                               = "SELECT SUM(costs.cost_amount_usdollars) as sum
         FROM costs
         WHERE costs.delegation_id = '".$this->id."'
         AND costs.type = 'accommodation'
         AND costs.deleted=0";
        $result                              = $this->db->query($query);
        $row                                 = $this->db->fetchByAssoc($result);
        $this->total_accommodation_usdollar = ( $row['sum'] > 0 ) ? $row['sum'] : 0;
        $this->total_accommodation           = $this->total_accommodation_usdollar / $this->getExchangeRate();
    }

    public function countNumberOfNights()
    {
        $date_start = new DateTime(substr($this->start_date, 0, 10));
        $date_end   = new DateTime(substr($this->end_date, 0, 10));
        $period     = $date_end->diff($date_start);
        return $period->d;
    }

    public function countAccommodationLumpSum($number_of_nights)
    {
        $nights_covered_by_lump_sum   = $number_of_nights - $this->assured_number_of_accommodations - $this->countAccommodationsWithLoggedCosts();
        $accommodation_value          = $nights_covered_by_lump_sum * $this->accommodation_value;
        $this->accommodation_lump_sum = ($accommodation_value > 0) ? $accommodation_value : 0;
    }

    public function countAccommodationsWithLoggedCosts()
    {
        if (empty($this->id)) {
            return 0;
        }
        $query = "SELECT SUM( accommodation_no )
         FROM costs
         WHERE costs.delegation_id = '".$this->id."'
         AND costs.type = 'accommodation'
         AND costs.deleted=0";
        return $this->db->getOne($query);
    }

    public function countOtherCosts()
    {
        if (empty($this->id)) {
            $this->other = 0;
            return;
        }
        $query                = "SELECT SUM(costs.cost_amount_usdollars) as sum
         FROM costs
         WHERE costs.delegation_id = '".$this->id."'
         AND (costs.type = 'restaurant' OR costs.type = 'other')
         AND costs.deleted=0";
        $result               = $this->db->query($query);
        $row                  = $this->db->fetchByAssoc($result);
        $this->other_usdollar = ( $row['sum'] > 0 ) ? $row['sum'] : 0;
        $this->other          = $this->other_usdollar / $this->getExchangeRate();
    }

    public function countTotalExpenses()
    {
        $this->total_expenses = $this->other + $this->accommodation_lump_sum + $this->total_accommodation + $this->regiments
            + $this->transport_cost;
    }

    public function countPayoffSum()
    {
        $this->payoff_sum = ($this->total_expenses - $this->obtained_sum) > 0 ? ($this->total_expenses - $this->obtained_sum)
                : 0;
    }

    public function countReturnSum()
    {
        $this->return_sum = ($this->total_expenses - $this->obtained_sum) < 0 ? ($this->obtained_sum - $this->total_expenses)
                : 0;
    }
}