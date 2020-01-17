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
require_once('modules/Costs/Costs_sugar.php');
require_once 'modules/Transportations/Transportations.php';
require_once('modules/Costs/SugarFeeds/CostsFeed.php');

class Costs extends Costs_sugar
{

    public function save($check_notify = false)
    {
        $this->setCostFields();

        $result = parent::save($check_notify);

        $costs_feed = new CostsFeed();
        $costs_feed->pushFeed($this, null, null);

        $rel_map = array(
            'Delegations' => 'delegation_id',
            'Transportations' => 'transportation_id',
        );

        foreach ($rel_map as $key => $value) {
            if ($this->$value && ( $this->cost_amount != $this->fetched_row['cost_amount'] || $this->$value != $this->fetched_row[$value] )) {
                $this->countCostsInRelatedModule($this->$value, $key);
            }

            if ($this->fetched_row[$value] && $this->$value != $this->fetched_row[$value]) {
                $this->countCostsInRelatedModule($this->fetched_row[$value], $key);
            }
        }

        return $result;
    }

    public function mark_deleted($id)
    {
        parent::mark_deleted($id);

        if ($this->fetched_row['delegation_id']) {
            $this->countCostsInRelatedModule($this->fetched_row['delegation_id'], 'Delegations');
        }

        if ($this->fetched_row['transportation_id']) {
            $this->countCostsInRelatedModule($this->fetched_row['transportation_id'], 'Transportations');
        }
    }

    public function setCostFields()
    {
        if ($this->type != 'accommodation') {
            $this->accommodation_no = "";
        } else if (empty($this->accommodation_no)) {
            $this->accommodation_no = "1";
        }

        if (!empty($this->cost_amount)) {
            $this->convertCostAmount();
        }

        if ($this->type == 'transport') {
            $this->getValuesFromTransportation();
        }

        $this->name = sprintf("%s %s %s", translate('LBL_TITLE', 'Costs'), $this->cost_date,
            translate('cost_type', '', $this->type));
    }

    public function convertCostAmount()
    {
        $this->cost_amount_usdollars = $this->cost_amount;
        $exchange_rate               = $this->getExchangeRateFromDelegation();
        if ($exchange_rate) {
            $this->cost_amount_usdollars = $this->cost_amount * $exchange_rate;
        } else {
            $currency                    = new Currency();
            $currency->retrieve($this->currency_id);
            $this->cost_amount_usdollars = $currency->convertToDollar($this->cost_amount);
        }
    }

    protected function getExchangeRateFromDelegation()
    {
        if ($this->currency_id == "-99") {
            return 1;
        }
        if (!empty($this->delegation_id)) {
            $delegation_id = $this->delegation_id;
        } else {
            $trans         = BeanFactory::getBean('Transportations', $this->transportation_id);
            $delegation_id = $trans->delegation_id;
        }
        if ($delegation_id) {
            $delegation = BeanFactory::getBean('Delegations', $delegation_id);
            if ($delegation && $delegation->id && $delegation->exchange_rate) {
                return $delegation->exchange_rate;
            }
        } else {
            return 1;
        }
    }

    public function countCostsInRelatedModule($id, $module_name)
    {
        $bean = BeanFactory::getBean($module_name, $id);
        if ($bean && $bean->id) {
            $bean->save();
        }
    }

    public function getValuesFromTransportation()
    {
        global $timedate;
        $transportation = BeanFactory::getBean('Transportations', $this->transportation_id);
        if ($transportation && $transportation->id) {
            $this->delegation_id = $transportation->delegation_id;
            if (empty($this->cost_date) && !empty($transportation->trans_date)) {
                $this->cost_date = $timedate->to_db_date($transportation->trans_date);
            }
        }
    }
}