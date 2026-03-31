<?php


namespace MintHCM\Lib\MintLogic\Validators;

use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Validator;

class ReservationValidator extends Validator
{
    public function validate($bean, $field = null)
    {
        $db = \DBManagerFactory::getInstance();

        
        $query = sprintf(
            "SELECT id FROM %s WHERE deleted = 0 AND resource_id = '%s' AND id != '%s' AND (
                (starting_date <= '%s' AND ending_date > '%s') OR
                (starting_date < '%s' AND ending_date >= '%s') OR
                (starting_date >= '%s' AND ending_date <= '%s')
            )",
            $bean->table_name,
            $bean->resource_id,
            $bean->id,
            $bean->starting_date, $bean->starting_date,
            $bean->ending_date, $bean->ending_date,
            $bean->starting_date, $bean->ending_date
        );

        if ($db->getOne($query)) {
            throw new ValidationException('ERR_RESERVATION_TIME_CONFLICT');
        }
    }
}