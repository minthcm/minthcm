<?php

namespace MintHCM\Lib\MintLogic\Modules\WorkSchedules\Validators;

use DBManagerFactory;
use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Validator;
use SugarDateTime;

class WorkSchedulesTypeExistsValidator extends Validator
{
    public function validate($bean, $field = null)
    {
        $db = DBManagerFactory::getInstance();
        if ('' != $bean->assigned_user_id && '' != $bean->date_start && '' != $bean->date_end) {
            $db_date_start = (new SugarDateTime($bean->date_start))->asDb(false);
            $db_date_end = (new SugarDateTime($bean->date_end))->asDb(false);
            if ($db->getOne($this->getWorkSchedulesBetweenDatesCountSQL($bean, $db_date_start, $db_date_end)) > 0) {
                throw new ValidationException('LBL_WORKSCHEDULES_FOR_THIS_PERIOD_ALREADY_EXISTS');
            }
        }
    }

    protected function getWorkSchedulesBetweenDatesCountSQL($bean, string $db_date_start, string $db_date_end): string
    {
        return " SELECT
                COUNT(id) as counter
            FROM
                workschedules
            WHERE
                assigned_user_id = '{$bean->assigned_user_id}'
                AND ((
                    (date_start BETWEEN '$db_date_start' AND '$db_date_end')
                )
                OR (
                    (date_end BETWEEN '$db_date_start' AND '$db_date_end')
                ))
                AND id != '{$bean->id}'
                AND deleted = 0
        ";
    }
}
