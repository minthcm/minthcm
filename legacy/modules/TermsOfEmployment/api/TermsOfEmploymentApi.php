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

class TermsOfEmploymentApi
{

    public function validateTermDates($args)
    {
        global $db;

        $id_select = !empty($args['id']) ? "AND id != '{$args['id']}'" : '';
        $query = "SELECT id 
        FROM termsofemployment 
            WHERE 
                deleted=0 
                AND contract_id = '{$args['contract_id']}'
                {$id_select}";

        $sql_result = $db->query($query);

        if(isset($sql_result->num_rows) && $sql_result->num_rows == 0) {
            return true;
        }
        

        $date = new DateTime($args['date_start']);
        $startDate = $date->format('Y-m-d');

        if(empty($args['date_end'])) {
            $args['date_end'] = $args['date_start'];
        }

        $date = new DateTime($args['date_end']);
        $endDate = $date->format('Y-m-d');

        $query = "SELECT id 
        FROM termsofemployment 
            WHERE 
                deleted=0 
                AND contract_id = '{$args['contract_id']}' 
                {$id_select} 
                AND (
                    (
                    DATE(term_starting_date) BETWEEN DATE('{$startDate}') AND DATE('{$endDate}') 
                    OR DATE(term_ending_date) BETWEEN DATE('{$startDate}') AND DATE('{$endDate}')
                    )
                    OR
                    DATE(term_ending_date) >= DATE('{$endDate}')
                    OR
                    term_ending_date IS NULL
                )";

        $sql_result = $db->query($query);

        if(isset($sql_result->num_rows) && $sql_result->num_rows > 0) {
            return false;
        }

        $query = "SELECT id
        FROM termsofemployment
            WHERE 
                deleted=0 
                AND contract_id = '{$args['contract_id']}' 
                {$id_select} 
                AND DATE(term_ending_date) = DATE_ADD(DATE('{$startDate}'), INTERVAL -1 DAY)";

        $sql_result = $db->query($query);

        return !isset($sql_result->num_rows) || $sql_result->num_rows == 1;
    }

    public function checkIfTermInBetween($args)
    {
        $info = $this->getTermsOfEmploymentInfo($args['id']);
        if (empty($info)) {
            return false;
        }

        return !empty($this->getIdsOfOuterTerms(
            $info['contract_id'],
            $info['term_starting_date'],
            $info['term_ending_date']
        ));
    }

    protected function getTermsOfEmploymentInfo($id)
    {
        global $db;
        $query = "SELECT term_starting_date, term_ending_date, contract_id "
            . "FROM termsofemployment "
            . "WHERE id='$id'";
        $result = $db->query($query);
        return $db->fetchByAssoc($result);
    }

    protected function getIdsOfOuterTerms($contract_id, $term_starting_date, $term_ending_date)
    {
        global $db;
        $query = "SELECT t1.id, t2.id "
            . "FROM termsofemployment t1 JOIN termsofemployment t2 ON t1.contract_id = t2.contract_id "
            . "WHERE t1.deleted = 0 AND t2.deleted = 0 AND t1.contract_id = '$contract_id'"
            . "AND t1.term_ending_date < '$term_starting_date' AND t2.term_starting_date > "
            . "IF ('$term_ending_date' != '', '$term_ending_date', '2099-12-31')";
        $result = $db->query($query);
        return $db->fetchByAssoc($result);
    }
}
