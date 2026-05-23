<?php

namespace MintHCM\Lib\MintLogic\Modules\WorkSchedules\Validators;

use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Validator;

class WorkSchedulesTypeWorkOffValidator extends Validator
{
    public function validate($bean, $field = null)
    {
        $db = \DBManagerFactory::getInstance();
        if (
            !empty($bean->id)
            && !empty($bean->type)
            && in_array($bean->type, ['holiday', 'sick', 'sick_care', 'occasional_leave', 'leave_at_request', 'overtime', 'excused_absence'])
        ) {
            if (!empty($db->getOne($this->getWorkSchedulesSpentTimeSQL($bean)))) {
                throw new ValidationException('LBL_ERR_CANT_CHANGE_TYPE_TO_WORK_OFF');
            }
        }
    }

    protected function getWorkSchedulesSpentTimeSQL($bean): string
    {
        return "SELECT
                    id
                FROM
                    workschedules_spenttime
                WHERE
                    workschedule_id = '{$bean->id}'
                    AND deleted = 0
        ";
    }
}
