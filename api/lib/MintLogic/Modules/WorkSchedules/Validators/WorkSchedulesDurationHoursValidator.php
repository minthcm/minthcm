<?php

namespace MintHCM\Lib\MintLogic\Modules\WorkSchedules\Validators;

use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Validator;
use SugarDateTime;

class WorkSchedulesDurationHoursValidator extends Validator
{
    public function validate($bean, $field = null)
    {
        if (!empty($bean->date_start) && !empty($bean->date_end)) {
            $date_start = new SugarDateTime($bean->date_start);
            $date_end = new SugarDateTime($bean->date_end);
            $diff_seconds = $date_end->getTimestamp() - $date_start->getTimestamp();
            if ($diff_seconds > 24 * 60 * 60) {
                throw new ValidationException('LBL_WORKSCHEDULES_LENGHT_OVER_ONE_DAY');
            }
        }
    }
}
