<?php

namespace MintHCM\Lib\MintLogic\Modules\WorkSchedules\Validators;

use MintHCM\Data\BeanFactory;
use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Validator;

class WorkSchedulesWorkplaceValidator extends Validator
{
    public function validate($bean, $field = null)
    {
        if (!empty($bean->workplace_id)) {
            $workplace = BeanFactory::getBean('Workplaces', $bean->workplace_id);
            if (!empty($workplace->id) && $workplace->id === $bean->workplace_id && 'active' !== $workplace->availability) {
                throw new ValidationException('LBL_ERR_STATUS_NOT_ACTIVE');
            }
            if (!empty($workplace->id) && $workplace->id === $bean->workplace_id) {
                $workplace->load_relationship('workplaces_allocations');
                $allocations = $workplace->workplaces_allocations->getBeans();
                if (is_array($allocations)) {
                    foreach ($allocations as $allocation) {
                        $start_date = strtotime($bean->date_start);
                        $end_date = strtotime($bean->date_end);
                        $from_date = strtotime($allocation->date_from);
                        $to_date = strtotime($allocation->date_to);
                        if (
                            !(
                                (empty($to_date) && $start_date >= $from_date)
                                || ($start_date >= $from_date && $end_date <= $to_date)
                            )
                        ) {
                            throw new ValidationException('LBL_ERR_WORKPLACE_NOT_ACTIVE');
                        }
                    }
                }
            }
        }
    }
}
