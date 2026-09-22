<?php

namespace MintHCM\Lib\MintLogic\Modules\TermsOfEmployment\Validators;

use DBManagerFactory;
use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Validator;

class LastTermClosedValidator extends Validator
{
    public function validate($bean, $field = null)
    {
        if (empty($bean->contract_id)) {
            return;
        }

        $db = DBManagerFactory::getInstance();
        $id_select = !empty($bean->id) ? "AND id != '{$bean->id}'" : '';

        $sql = "SELECT id
                FROM termsofemployment
                WHERE deleted = 0
                  AND contract_id = '{$bean->contract_id}'
                  AND term_ending_date IS NULL
                  {$id_select}";

        $sql_result = $db->query($sql);

        if (isset($sql_result->num_rows) && $sql_result->num_rows > 0) {
            throw new ValidationException('LBL_LAST_TERM_NOT_CLOSED');
        }
    }
}
