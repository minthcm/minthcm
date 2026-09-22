<?php

namespace MintHCM\Lib\MintLogic\Modules\TermsOfEmployment\Validators;

use DBManagerFactory;
use MintHCM\Lib\MintLogic\Validator;

class ContractTermDatesReadonlyValidator extends Validator
{
    public function validate($bean, $field = null)
    {
        if (!empty($bean->id)) {
            $db = DBManagerFactory::getInstance();
            $info = $db->fetchByAssoc($db->query($this->getTermsOfEmploymentReadonlyFieldsDataSQL($bean->id)));
            if (empty($info)) {
                return [
                    'contract_name' => false,
                    'term_starting_date' => false,
                    'term_ending_date' => false,
                ];
            }
            if (!empty($db->fetchByAssoc($db->query($this->getDifferentTermsOfEmploymentIdsByTermDatesDataSQL($bean->contract_id, $bean->term_starting_date, $bean->term_ending_date))))) {
                return [
                    'contract_name' => true,
                    'term_starting_date' => true,
                    'term_ending_date' => true,
                ];
            }
        }
        return [
            'contract_name' => false,
            'term_starting_date' => false,
            'term_ending_date' => false,
        ];
    }

    protected function getTermsOfEmploymentReadonlyFieldsDataSQL(string $id): string
    {
        return "SELECT
                    term_starting_date, term_ending_date, contract_id
                FROM
                    termsofemployment
                WHERE
                    id='{$id}'
        ";
    }

    protected function getDifferentTermsOfEmploymentIdsByTermDatesDataSQL(string $contract_id, string $term_starting_date, string $term_ending_date): string
    {
        return "SELECT
                    t1.id, t2.id
                FROM
                    termsofemployment t1
                JOIN
                    termsofemployment t2 ON t1.contract_id = t2.contract_id
                WHERE
                    t1.deleted = 0
                    AND t2.deleted = 0
                    AND t1.contract_id = '{$contract_id}'
                    AND t1.term_ending_date < '{$term_starting_date}'
                    AND t2.term_starting_date > IF ('{$term_ending_date}' != '', '{$term_ending_date}', '2099-12-31')
        ";
    }
}
