<?php

namespace MintHCM\Lib\MintLogic\Validators;

use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Validator;

class IsUnique extends Validator
{
    public function validate($bean, $field = 'name')
    {
        if (empty($bean->$field)) {
            return;
        }
        if ($bean->db->getOne("SELECT id FROM {$bean->table_name} WHERE deleted = 0 AND {$field} = '{$bean->$field}' AND id != '{$bean->id}'")) {
            throw new ValidationException('ERR_NOT_UNIQUE');
        }
    }
}
