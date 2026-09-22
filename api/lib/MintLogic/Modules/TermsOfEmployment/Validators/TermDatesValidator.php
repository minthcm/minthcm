<?php

namespace MintHCM\Lib\MintLogic\Modules\TermsOfEmployment\Validators;

use DBManagerFactory;
use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Validator;
use SugarDateTime;

class TermDatesValidator extends Validator
{
    public function validate($bean, $field = null)
    {
        $this->validateEndDateBefore($bean);
        $this->validateTermDates($bean);
    }

    protected function validateEndDateBefore($bean): void
    {
        $ending_date = strtotime($bean->term_ending_date);
        $starting_date = strtotime($bean->term_starting_date);
        if (!empty($ending_date) && $starting_date > $ending_date) {
            throw new ValidationException('LBL_DATE_END_ERROR');
        }
    }

    protected function validateTermDates($bean): void
    {
        $db = DBManagerFactory::getInstance();

        $id_select = !empty($bean->id) ? "AND id != '{$bean->id}'" : '';

        $sql_result = $db->query($this->getTermsOfEmploymentIdsByContractIdSQL($bean->contract_id, $id_select));

        if (isset($sql_result->num_rows) && 0 == $sql_result->num_rows) {
            return;
        }

        $date = new SugarDateTime($bean->term_starting_date);
        $startDate = $date->format('Y-m-d');
        $date = new SugarDateTime(empty($bean->term_ending_date) ? $bean->term_starting_date : $bean->term_ending_date);
        $endDate = $date->format('Y-m-d');

        $sql_result = $db->query($this->getTermsOfEmploymentIdsBetweenTermDatesSQL($bean->contract_id, $startDate, $endDate, $id_select));

        if (isset($sql_result->num_rows) && $sql_result->num_rows > 0) {
            throw new ValidationException('LBL_TERMS_NOT_ADJECENT');
        }

        $sql_result = $db->query($this->getTermsOfEmploymentIdsByEndingStartingDateSQL($bean->contract_id, $startDate, $id_select));
        if (!empty($sql_result->num_rows) && $sql_result->num_rows > 1) {
            throw new ValidationException('LBL_TERMS_NOT_ADJECENT');
        }
    }

    protected function getTermsOfEmploymentIdsByContractIdSQL(string $contract_id, string $id_select = ''): string
    {
        return "SELECT
                    id
                FROM
                    termsofemployment
                WHERE
                    deleted=0
                    AND contract_id = '{$contract_id}'
                    {$id_select}
        ";
    }

    protected function getTermsOfEmploymentIdsBetweenTermDatesSQL(string $contract_id, string $startDate, string $endDate, string $id_select = ''): string
    {
        return "SELECT
                        id
                    FROM
                        termsofemployment
                    WHERE
                        deleted=0
                        AND contract_id = '{$contract_id}'
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
                        )
        ";
    }

    protected function getTermsOfEmploymentIdsByEndingStartingDateSQL(string $contract_id, string $startDate, string $id_select = ''): string
    {
        return "SELECT
                        id
                    FROM
                        termsofemployment
                    WHERE
                        deleted=0
                        AND contract_id = '{$contract_id}'
                        {$id_select}
                        AND DATE(term_ending_date) = DATE_ADD(DATE('{$startDate}'), INTERVAL -1 DAY)
        ";
    }
}
