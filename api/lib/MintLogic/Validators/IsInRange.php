<?php
namespace MintHCM\Lib\MintLogic\Validators;

use MintHCM\Lib\MintLogic\Exceptions\ValidationException;

class IsInRange
{
    public static function validate($bean, ?string $field = '', $min = null, $max = null)
    {
        if ($bean->$field === null || $bean->$field === '') {
            return;
        }
        $below_min = floatval($bean->$field) < $min;
        $above_max = floatval($bean->$field) > $max;
        if (is_numeric($min) && is_numeric($max) && ($below_min || $above_max)) {
            throw new ValidationException(translate('LBL_ESLIST_FIELD') . ' ' . translate('LBL_VALIDATE_RANGE') . ' (' . $min . ' - ' . $max . ')');
        }
        if (is_numeric($min) && $below_min) {
            throw new ValidationException(translate('LBL_ESLIST_FIELD') . ' ' . translate('MSG_SHOULD_BE') . ' ' . $min . ' ' . translate('MSG_OR_GREATER'));
        }
        if (is_numeric($max) && $above_max) {
            throw new ValidationException(translate('LBL_ESLIST_FIELD') . ' ' . 'MSG_IS_MORE_THAN' . ' ' . $max);
        }
    }
}
