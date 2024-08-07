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

class AllocationsApi
{

    public function checkWorkplacePeriods($args)
    {
        $result = true;
        if ('permanent' == $args['mode']) {
            $result = $this->checkConcurrentPeriods($args['id'], $args['workplace_id'], $args['date_from'], $args['date_to']);
        }
        return $result;
    }
    public function checkWorkplaceStatus($args)
    {
        $workplace = BeanFactory::getBean('Workplaces', $args['workplace_id']);
        if ('active' != $workplace->availability) {
            return false;
        } else if ('permanent' == $workplace->mode && 'permanent' != $args['mode']) {
            return false;
        } else if ('rotational' == $args['mode'] && 'permanent' == $workplace->mode) {
            return false;
        } else {
            return true;
        }

    }
    protected function checkConcurrentPeriods($id, $workplace_id, $date_from, $date_to)
    {
        $workplace = BeanFactory::getBean('Workplaces', $workplace_id);
        $workplace->load_relationship('workplaces_allocations');
        $allocations = $workplace->workplaces_allocations->getBeans();

        foreach($allocations as $allocation){
            if ($allocation->id === $id) {
                continue;
            }

            $start_date = strtotime($allocation->date_from);
            $end_date = strtotime($allocation->date_to);
            $from_date = strtotime($date_from);
            $to_date = strtotime($date_to);
            if (empty($end_date) && empty($to_date)) {
                return false;
            } else if (empty($end_date) && !empty($to_date)) {
                if ($from_date > $start_date || $to_date > $start_date) {
                    return false;
                }

            } else if (!empty($end_date) && empty($to_date)) {
                if ($from_date <= $end_date) {
                    return false;
                }

            } else {
                if (($end_date >= $from_date && $start_date <= $from_date) ||
                    ($end_date <= $from_date && $start_date >= $from_date)) {
                    return false;
                }

            }
        }
        return true;
    }
}
