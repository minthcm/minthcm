<?php

namespace MintHCM\Lib\MintLogic\Modules\SpentTime\Validators;

use DBManagerFactory;
use MintHCM\Data\MintDateTime;
use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Validator;

class SpentTimeOverlapValidator extends Validator
{
    public function validate($bean, $field = null): void
    {
        if (empty($bean->workschedule_id) || empty($bean->date_start) || empty($bean->date_end)) {
            return;
        }

        $dt_start = MintDateTime::getMintDateTimeFromString($bean->date_start);
        $dt_end = MintDateTime::getMintDateTimeFromString($bean->date_end);

        if ($dt_start === null || $dt_end === null) {
            return;
        }

        $date_start = $dt_start->format('Y-m-d H:i:s');
        $date_end = $dt_end->format('Y-m-d H:i:s');

        $db = DBManagerFactory::getInstance();
        $sql = $this->buildOverlapQuery($db, $bean->workschedule_id, $date_start, $date_end, $bean->id ?? null);
        $count = intval($db->getOne($sql));

        if ($count !== 0) {
            throw new ValidationException('LBL_SPENT_TIME_RECORD_FOR_THIS_PERIOD_ALREADY_EXISTS');
        }
    }

    protected function buildOverlapQuery($db, string $workschedule_id, string $date_start, string $date_end, ?string $bean_id): string
    {
        $id_condition = !empty($bean_id) ? "st.id != " . $db->quoted($bean_id) . " AND " : '';

        return "SELECT COUNT(st.id) AS count FROM workschedules_spenttime ws "
            . "LEFT JOIN spenttime st ON "
            . "ws.spenttime_id = st.id "
            . "AND ws.workschedule_id = " . $db->quoted($workschedule_id) . " WHERE "
            . "st.deleted = 0 AND "
            . $id_condition
            . "((st.date_start <= '{$date_start}' AND st.date_end > '{$date_start}') OR "
            . "(st.date_start < '{$date_end}' AND st.date_end >= '{$date_end}') OR "
            . "(st.date_start < '{$date_start}' AND st.date_end > '{$date_start}') OR "
            . "(st.date_start > '{$date_start}' AND st.date_end < '{$date_end}'))";
    }
}
