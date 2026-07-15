<?php

namespace MintHCM\Lib\MintLogic\Modules\SpentTime\Updaters;

use DBManagerFactory;
use MintHCM\Data\MintDateTime;

class SetDatesFromWorkscheduleUpdater
{
    public function __invoke($bean): array
    {
        if (empty($bean->workschedule_id) || !empty($bean->id)) {
            return [];
        }

        $db = DBManagerFactory::getInstance();
        $now = new MintDateTime();

        $date_end = $this->getDateEnd($db, $bean->workschedule_id, $now);
        $date_start = $this->getDateStart($db, $bean->workschedule_id);

        $result = ['date_end' => $date_end];

        if (!empty($date_start)) {
            $result['date_start'] = $date_start;
        }

        if (!empty($result['date_start']) && !empty($result['date_end'])) {
            $spent_time = $this->calculateSpentTime($result['date_start'], $result['date_end']);
            if ($spent_time !== null) {
                $result['spent_time'] = $spent_time;
            }
        }

        return $result;
    }

    protected function getDateEnd($db, string $workschedule_id, MintDateTime $now): string
    {
        $workschedule_date_end = $db->getOne(
            "SELECT date_end FROM workschedules WHERE deleted = 0 AND id = " . $db->quoted($workschedule_id)
        );

        $today = $now->format('Y-m-d');
        $workschedule_date_end_day = !empty($workschedule_date_end)
            ? (new MintDateTime($workschedule_date_end))->format('Y-m-d')
            : null;

        if (!empty($workschedule_date_end_day) && $workschedule_date_end_day !== $today) {
            return $workschedule_date_end;
        }

        return $this->getRoundedNow($now);
    }

    protected function getRoundedNow(MintDateTime $now): string
    {
        $now = clone $now;
        $minutes = (int) $now->format('i');
        $rounded_minutes = (int) (ceil($minutes / 5) * 5);
        if ($rounded_minutes === 60) {
            $now->modify('+1 hour');
            $rounded_minutes = 0;
        }
        $now->setTime((int) $now->format('H'), $rounded_minutes, 0);
        return $now->format('Y-m-d H:i:s');
    }

    protected function getDateStart($db, string $workschedule_id): ?string
    {
        $last_date_end = $this->getLastSpentTimeEnd($db, $workschedule_id);
        if (!empty($last_date_end)) {
            return $last_date_end;
        }

        return $this->getWorkscheduleStartDate($db, $workschedule_id);
    }

    protected function getLastSpentTimeEnd($db, string $workschedule_id): ?string
    {
        $sql = "SELECT st.date_end
                FROM workschedules_spenttime ws
                LEFT JOIN spenttime st ON ws.spenttime_id = st.id
                WHERE ws.workschedule_id = " . $db->quoted($workschedule_id) . "
                AND st.deleted = 0
                ORDER BY st.date_end DESC
                LIMIT 1";
        return $db->getOne($sql) ?: null;
    }

    protected function getWorkscheduleStartDate($db, string $workschedule_id): ?string
    {
        $result = $db->getOne(
            "SELECT date_start FROM workschedules WHERE deleted = 0 AND id = " . $db->quoted($workschedule_id)
        );
        return $result ?: null;
    }

    protected function calculateSpentTime(string $date_start, string $date_end): ?float
    {
        $ds = new MintDateTime($date_start);
        $de = new MintDateTime($date_end);
        if ($de <= $ds) {
            return null;
        }
        return round(($de->getTimestamp() - $ds->getTimestamp()) / 3600, 2);
    }
}
