<?php
namespace MintHCM\Utils;

use DateInterval;
use DateTime;
use MintHCM\Data\BeanFactory;
use MintHCM\Data\MintBean;
use MintHCM\Data\TimeDate;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class CyclicRecordsSaver
{
    const REPEAT_LIMIT = 250;

    const ALLOWED_REPEAT_TYPES = [
        'daily',
        'every_weekday',
        'weekly',
        'every_two_weeks',
        'monthly',
        'yearly',
        'custom',
    ];

    const ALLOWED_WEEK_DAYS = [
        1 => 'Sunday',
        2 => 'Monday',
        3 => 'Tuesday',
        4 => 'Wednesday',
        5 => 'Thursday',
        6 => 'Friday',
        7 => 'Saturday',
    ];

    protected const SKIP_FIELDS = [
        'id',
        'date_entered',
        'date_modified',
        'date_indexed',
    ];

    /**
     * Fields that must not be overwritten when propagating parent changes to
     * cyclic children. date_start / date_end belong to each child independently;
     * repeat_* fields carry the original rules and should not be touched.
     */
    const UPDATE_SKIP_FIELDS = [
        'id',
        'date_entered',
        'date_modified',
        'date_indexed',
        'date_start',
        'date_end',
        'repeat_parent_id',
        'repeat_type',
        'repeat_interval',
        'repeat_dow',
        'repeat_until',
        'repeat_count',
    ];

    const RELATIONSHIP_LINKS_TO_COPY = [
        'Meetings' => [
            'users',
            'candidates',
            'resources',
        ],
        'Calls' => [
            'users',
            'candidates',
            'resources',
        ],
    ];

    protected $timedate;

    public function __construct(protected $bean, protected EntityManagerInterface $entityManager)
    {
        $this->timedate = new TimeDate();
    }

    public function isCyclicRecord(): bool
    {
        return !empty($this->bean->repeat_parent_id);
    }

    /**
     * Calculate and return all cyclic record date pairs that would be created
     * for this bean, without actually saving anything.
     *
     * @return array<int, array{date_start: string, date_end: string}>
     * @throws \Exception if validation fails
     */
    public function plan(): array
    {
        if (
            empty($this->bean->id)
            || empty($this->bean->repeat_type)
            || $this->hasCyclicRecords()
            || $this->isCyclicRecord()
        ) {
            return [];
        }
        $this->validate();
        // Bean datetime values are in user-local format after SugarCRM's retrieve() conversion.
        // Use timedate->to_db() to get the actual UTC strings before parsing with DateTime.
        $date_start_object = new DateTime($this->timedate->to_db($this->bean->date_start));
        $date_end_object = new DateTime($this->timedate->to_db($this->bean->date_end));
        $dates_diff = $date_start_object->diff($date_end_object);
        $next_start_date_objects = $this->calculateStartDates(clone $date_start_object);
        if (count($next_start_date_objects) > static::REPEAT_LIMIT) {
            $next_start_date_objects = array_slice($next_start_date_objects, 0, static::REPEAT_LIMIT);
        }
        $records = [];
        foreach ($next_start_date_objects as $next_start_date_object) {
            $next_start_date_object->setTime(
                $date_start_object->format('H'),
                $date_start_object->format('i'),
                $date_start_object->format('s'),
            );
            $next_end_date_object = clone $next_start_date_object;
            $next_end_date_object->add($dates_diff);
            $records[] = [
                'date_start' => $next_start_date_object->format('Y-m-d H:i:s'),
                'date_end'   => $next_end_date_object->format('Y-m-d H:i:s'),
            ];
        }
        return $records;
    }

    /**
     * Return the IDs of all related beans per relationship link for this bean.
     * Used so the caller can pass them back on every batch request, avoiding
     * repeated get_linked_beans() calls across HTTP requests.
     *
     * @return array<string, string[]>  e.g. ['users' => ['id1', 'id2'], ...]
     */
    public function getRelatedIds(): array
    {
        $module = $this->bean->module_dir;
        if (!array_key_exists($module, static::RELATIONSHIP_LINKS_TO_COPY)) {
            return [];
        }
        $related_ids = [];
        foreach (static::RELATIONSHIP_LINKS_TO_COPY[$module] as $field) {
            $beans = $this->bean->get_linked_beans($field);
            $related_ids[$field] = array_map(fn($b) => $b->id, $beans);
        }
        return $related_ids;
    }

    /**
     * Create cyclic records from a pre-calculated plan (array of date pairs).
     * Each item must contain 'date_start' and 'date_end' (Y-m-d H:i:s format).
     * Pass $related_ids (from getRelatedIds()) to avoid re-fetching relationships
     * on every batch request.
     *
     * @param array<int, array{date_start: string, date_end: string}> $records
     * @param array<string, string[]> $related_ids  Pre-collected relationship IDs
     * @return int number of records created
     */
    public function createFromPlan(array $records, array $related_ids = []): int
    {
        $created = 0;
        foreach ($records as $record) {
            $this->createCyclicRecord(
                new DateTime($record['date_start']),
                new DateTime($record['date_end']),
                $related_ids,
            );
            $created++;
        }
        return $created;
    }

    /**
     * Return the IDs of all cyclic children of this bean so the frontend can
     * split them into chunks and POST them back via updateFromPlan().
     *
     * @return string[]
     */
    public function planUpdate(): array
    {
        if (empty($this->bean->id) || $this->isCyclicRecord()) {
            return [];
        }
        $entity_class = $this->getEntityClassName();
        $queryBuilder = $this->entityManager->createQueryBuilder($entity_class);
        $children = $queryBuilder->select('e.id')
            ->from($entity_class, 'e')
            ->where('e.repeat_parent_id = :parentId')
            ->andWhere('e.deleted = 0')
            ->setParameter('parentId', $this->bean->id)
            ->getQuery()
            ->getArrayResult();
        return array_column($children, 'id');
    }

    /**
     * Propagate the current state of the parent bean onto the cyclic children
     * whose IDs are listed in $ids.  Fields in UPDATE_SKIP_FIELDS (dates,
     * repeat settings, identity columns) are intentionally preserved on each
     * child.
     *
     * @param string[] $ids
     * @return int number of records updated
     */
    public function updateFromPlan(array $ids): int
    {
        $updated = 0;

        // Bean datetime values are in user-local format after SugarCRM's retrieve() conversion.
        // Use timedate->to_db() to get the actual UTC strings before parsing with DateTime.
        $parent_date_start = new DateTime($this->timedate->to_db($this->bean->date_start));
        $parent_date_end = new DateTime($this->timedate->to_db($this->bean->date_end));
        $parent_duration = $parent_date_start->diff($parent_date_end);
        $parent_related_ids = $this->getRelatedIds();

        foreach ($ids as $child_id) {
            $child_bean = BeanFactory::getBean($this->bean->module_name, $child_id);
            if (empty($child_bean->id)) {
                continue;
            }
            foreach ($this->bean->field_defs as $key => $value) {
                if (
                    in_array($key, static::UPDATE_SKIP_FIELDS)
                    || in_array($value['type'], ['link'])
                ) {
                    continue;
                }
                $child_bean->$key = $this->bean->$key;
            }
            // Propagate time-of-day from parent, keeping each child's unique calendar date.
            // Child's date_start is also user-local after retrieve, convert to UTC first.
            $child_start = new DateTime($this->timedate->to_db($child_bean->date_start));
            $child_start->setTime(
                (int) $parent_date_start->format('H'),
                (int) $parent_date_start->format('i'),
                (int) $parent_date_start->format('s'),
            );
            $child_end = clone $child_start;
            $child_end->add($parent_duration);
            // Store UTC strings directly; save() will not re-convert values matching the DB datetime pattern.
            $child_bean->date_start = $child_start->format('Y-m-d H:i:s');
            $child_bean->date_end = $child_end->format('Y-m-d H:i:s');
            $child_bean->save();
            $this->syncRelationshipFields($child_bean, $parent_related_ids);
            $updated++;
        }
        return $updated;
    }

    public function hasCyclicRecords(): bool
    {
        $entity_class = $this->getEntityClassName();
        $queryBuilder = $this->entityManager->createQueryBuilder($entity_class);
        $children = $queryBuilder->select('e.id')
            ->from($entity_class, 'e')
            ->where('e.repeat_parent_id = :parentId')
            ->andWhere('e.deleted = 0')
            ->setParameter('parentId', $this->bean->id)
            ->getQuery()
            ->getArrayResult();
            
        return !empty($children);
    }

    protected function getEntityClassName(): string
    {
        $moduleName = $this->bean->module_name;
        return "MintHCM\\Api\\Entities\\{$moduleName}";
    }

    protected function validate(): void
    {
        try {
            $date_start_object = new DateTime($this->bean->date_start);
        } catch (\Exception $e) {
            throw new Exception("Cannot parse date_start for bean ID {$this->bean->id}");
        }

        try {
            $date_end_object = new DateTime($this->bean->date_end);
        } catch (\Exception $e) {
            throw new Exception("Cannot parse date_end for bean ID {$this->bean->id}");
        }

        if ($date_end_object < $date_start_object) {
            throw new Exception("date_end cannot be before date_start for bean ID {$this->bean->id}");
        }

        if (!in_array($this->bean->repeat_type, static::ALLOWED_REPEAT_TYPES)) {
            throw new Exception("Invalid repeat_type for bean ID {$this->bean->id}");
        }

        if ($this->bean->repeat_type === 'custom') {
            if (empty($this->bean->repeat_interval) || (int) $this->bean->repeat_interval <= 0) {
                throw new Exception("Invalid repeat_interval for bean ID {$this->bean->id}");
            }
            if (empty($this->bean->repeat_interval_unit)) {
                throw new Exception("Invalid repeat_interval_unit for bean ID {$this->bean->id}");
            }
            if (
                $this->bean->repeat_interval_unit === 'week'
                && !empty($this->bean->repeat_dow)
            ) {
                $number_days_of_week = str_split($this->bean->repeat_dow);
                if (!empty(array_diff($number_days_of_week, array_keys(static::ALLOWED_WEEK_DAYS)))) {
                    throw new Exception("Invalid repeat_dow for bean ID {$this->bean->id}");
                }
            }
        }

        if (
            empty($this->bean->repeat_count)
            && empty($this->bean->repeat_until)
        ) {
            throw new Exception("Either repeat_count or repeat_until must be set for bean ID {$this->bean->id}");
        }

        if (
            !empty($this->bean->repeat_count)
            && (int) $this->bean->repeat_count <= 0
        ) {
            throw new Exception("Invalid repeat_count for bean ID {$this->bean->id}");
        }
        
        if (
            !empty($this->bean->repeat_until)
            && (
                new DateTime($this->bean->repeat_until . ' 23:59:59') < new DateTime($this->bean->date_start)
                || new DateTime($this->bean->repeat_until . ' 23:59:59') < new DateTime($this->bean->date_end)
            )
        ) {
            throw new Exception("Invalid repeat_until for bean ID {$this->bean->id}");
        }
    }

    protected function createCyclicRecord(DateTime $next_start_date_object, DateTime $next_end_date_object, array $related_ids = []): void
    {
        $new_bean = BeanFactory::newBean($this->bean->module_name);
        foreach ($this->bean->field_defs as $key => $value) {
            if (
                in_array($key, static::SKIP_FIELDS)
                || in_array($value['type'], ['link'])
            ) {
                continue;
            }
            $new_bean->$key = $this->bean->$key;
        }
        $new_bean->date_start = $next_start_date_object->format('Y-m-d H:i:s');
        $new_bean->date_end = $next_end_date_object->format('Y-m-d H:i:s');
        $new_bean->repeat_parent_id = $this->bean->id;
        $new_bean->save();
        $this->copyRelationshipFields($new_bean, $related_ids);
    }

    protected function calculateStartDates(DateTime $start_date_object): array
    {
        if (
            $this->bean->repeat_type === 'every_weekday'
            || (
                $this->bean->repeat_type === 'custom'
                && $this->bean->repeat_interval_unit === 'week'
                && !empty($this->bean->repeat_dow)
            )
        ) {
            return $this->calculateCustomStartDates($start_date_object);
        }
        return $this->calculateIntervalStartDates($start_date_object);
    }

    protected function calculateCustomStartDates(DateTime $start_date_object): array
    {
        if (!empty($this->bean->repeat_count)) {
            return $this->calculateCustomStartDatesByCount(
                $start_date_object,
                (int) $this->bean->repeat_count
            );
        }
        return $this->calculateCustomStartDatesByUntil(
            $start_date_object,
            $this->getRepeatUntilDateObject($start_date_object)
        );
    }

    protected function calculateIntervalStartDates(DateTime $start_date_object): array
    {
        if (!empty($this->bean->repeat_count)) {
            return $this->calculateIntervalStartDatesByCount(
                $start_date_object,
                (int) $this->bean->repeat_count
            );
        }
        return $this->calculateIntervalStartDatesByUntil(
            $start_date_object,
            $this->getRepeatUntilDateObject($start_date_object)
        );
    }

    protected function calculateCustomStartDatesByCount(DateTime $start_date_object, int $count): array
    {
        $start_date_objects = [];
        $date_interval = $this->getDateRepeatInterval();
        $expected_days_of_week = $this->getRepeatDOWNames();
        $count--;
        $days_back = (int) $start_date_object->format('w');
        $current_week_start_date_object = (clone $start_date_object)->modify("-{$days_back} days");
        while ($count > 0) {
            foreach ($expected_days_of_week as $day) {
                $candidate_date_start_object = (clone $current_week_start_date_object)->modify($day);
                if ($candidate_date_start_object <= $start_date_object) {
                    continue;
                }
                $start_date_objects[] = $candidate_date_start_object;
                $count--;
                if ($count <= 0) {
                    break 2;
                }
            }
            $current_week_start_date_object = (clone $current_week_start_date_object)->add($date_interval);
        }
        return $start_date_objects;
    }

    protected function calculateCustomStartDatesByUntil(DateTime $start_date_object, DateTime $until_date_object): array
    {
        $start_date_objects = [];
        $date_interval = $this->getDateRepeatInterval();
        $expected_days_of_week = $this->getRepeatDOWNames();
        $days_back = (int) $start_date_object->format('w');
        $current_week_start_date_object = (clone $start_date_object)->modify("-{$days_back} days");
        while ($current_week_start_date_object <= $until_date_object) {
            foreach ($expected_days_of_week as $day) {
                $candidate_date_start_object = (clone $current_week_start_date_object)->modify($day);
                if ($candidate_date_start_object <= $start_date_object) {
                    continue;
                }
                if ($candidate_date_start_object > $until_date_object) {
                    break 2;
                }
                $start_date_objects[] = $candidate_date_start_object;
            }
            $current_week_start_date_object = (clone $current_week_start_date_object)->add($date_interval);
        }
        return $start_date_objects;
    }

    protected function calculateIntervalStartDatesByCount(DateTime $start_date_object, int $count): array
    {
        $start_date_objects = [];
        $date_interval = $this->getDateRepeatInterval();
        $count--;
        $candidate_date_start_object = (clone $start_date_object)->add($date_interval);
        while ($count > 0) {
            $start_date_objects[] = $candidate_date_start_object;
            $count--;
            if ($count <= 0) {
                break;
            }
            $candidate_date_start_object = (clone $candidate_date_start_object)->add($date_interval);
        }
        return $start_date_objects;
    }

    protected function calculateIntervalStartDatesByUntil(DateTime $start_date_object, DateTime $until_date_object): array
    {
        $start_date_objects = [];
        $date_interval = $this->getDateRepeatInterval();
        $candidate_date_start_object = (clone $start_date_object)->add($date_interval);
        while ($candidate_date_start_object <= $until_date_object) {
            $start_date_objects[] = $candidate_date_start_object;
            $candidate_date_start_object = (clone $candidate_date_start_object)->add($date_interval);
        }
        return $start_date_objects;
    }

    protected function getRepeatDOWNames(): array
    {
        if ($this->bean->repeat_type === 'every_weekday') {
            return ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        }
        if ($this->bean->repeat_type === 'custom' && !empty($this->bean->repeat_dow)) {
            $days_of_week_numbers = array_unique(str_split($this->bean->repeat_dow));
            sort($days_of_week_numbers);
            $days_of_week_names = [];
            foreach ($days_of_week_numbers as $day_of_week_number) {
                $days_of_week_names[] = static::ALLOWED_WEEK_DAYS[(int) $day_of_week_number];
            }
            return $days_of_week_names;
        }
        return [];
    }

    protected function getDateRepeatInterval(): ?DateInterval
    {
        $date_interval = new DateInterval("P1D");
        switch ($this->bean->repeat_type) {
            case 'daily':
                $date_interval = new DateInterval("P1D");
                break;
            case 'every_weekday':
            case 'weekly':
                $date_interval = new DateInterval("P7D");
                break;
            case 'every_two_weeks':
                $date_interval = new DateInterval("P14D");
                break;
            case 'monthly':
                $date_interval = new DateInterval("P1M");
                break;
            case 'yearly':
                $date_interval = new DateInterval("P1Y");
                break;
            case 'custom':
                $date_interval = $this->getIntervalForCustom();
                break;
            default:
                break;
        }
        return $date_interval;
    }

    protected function getRepeatUntilDateObject(DateTime $start_date_object): DateTime
    {
        $repeat_until_object = new DateTime($this->bean->repeat_until);
        $repeat_until_object->setTime(
            $start_date_object->format('H'),
            $start_date_object->format('i'),
            $start_date_object->format('s'),
        );
        return $repeat_until_object;
    }

    protected function getDatesByUntil(array &$dates, DateTime $current_date, DateInterval $interval, string $until, array $days_of_week): void
    {
        $until_date = new DateTime($until . ' 23:59:59');
        if (!empty($days_of_week)) {
            $this->addOnGivenDays($dates, $current_date, $days_of_week, null, $until_date);
        }

        $current_date->add($interval);
    
        while ($current_date <= $until_date) {
            if (!empty($days_of_week)) {
                $this->addOnGivenDays($dates, $current_date, $days_of_week, null, $until_date);
                $current_date->add($interval);
                continue;
            }

            $dates[] = $this->formatDateToDb($current_date);
            $current_date->add($interval);
        }
    }
    protected function getDatesByCount(array &$dates, DateTime $current_date, DateInterval $interval, int $count, array $days_of_week): void
    {
        if (!empty($days_of_week)) {
            $this->addOnGivenDays($dates, $current_date, $days_of_week, $count);
        }
        
        while (count($dates) < $count) {
            $current_date->add($interval);
            if (!empty($days_of_week)) {
                $this->addOnGivenDays($dates, $current_date, $days_of_week, $count);
                continue;
            }

            $dates[] = $this->formatDateToDb($current_date);
        }
    }

    protected function addOnGivenDays(array &$dates, DateTime $current_date, array $days_of_week, ?int $count = null, ?DateTime $until = null): void
    {
        if (!empty($count)) {
            $count_dates = [];
            foreach ($days_of_week as $day) {
                $date = $this->calculateDateByDay($current_date, $day);
                if($this->formatDateToDb($date) !== $this->formatDateToDb($current_date)) {
                    $count_dates[] = $this->formatDateToDb($date);
                }
            }

            usort($count_dates, function($a, $b) {
                return strtotime($a) <=> strtotime($b);
            });

            foreach($count_dates as $c_date) {
                if (count($dates) < $count) {
                    $dates[] = $c_date;
                    continue;
                }

                break;
            }
        } else if (!empty($until)) {
            foreach ($days_of_week as $day) {
                $date = $this->calculateDateByDay($current_date, $day);
                if ($date > $until) {
                    continue;
                }

                if ($this->formatDateToDb($date) !== $this->formatDateToDb($current_date)) {
                    $dates[] = $this->formatDateToDb($date);
                }
            }
        }
    }

    protected function calculateDateByDay(DateTime $current_date, string $day): DateTime
    {
        global $app_list_strings;
        $day_name = strtolower($app_list_strings['dom_cal_day_long'][$day]);
        $date = clone $current_date;
        $current_day_name = strtolower($date->format('l'));
        if ($current_day_name === $day_name && $this->formatDateToDb($date) !== $this->bean->date_start) {
            return $date;
        }

        $original_time = $date->format('H:i:s');
        $date->modify("next $day_name");
        $date->setTime(...explode(':', $original_time));

        return $date;
    }

    protected function calculateEndDate(string $start_date, DateInterval $duration): string
    {
        $start_date_object = new DateTime($start_date);
        $end_date_object = clone $start_date_object;
        $end_date_object->add($duration);
        return $this->formatDateToDb($end_date_object);
    }

    protected function copyRelationshipFields(MintBean $new_bean, array $related_ids = []): void
    {
        $module = $this->bean->module_dir;
        if (!array_key_exists($module, static::RELATIONSHIP_LINKS_TO_COPY)) {
            return;
        }

        foreach (static::RELATIONSHIP_LINKS_TO_COPY[$module] as $field) {
            $ids = $related_ids[$field] ?? [];
            if (empty($ids)) {
                continue;
            }
            if ($new_bean->load_relationship($field)) {
                foreach ($ids as $id) {
                    $new_bean->$field->add($id);
                }
            }
        }
    }

    protected function syncRelationshipFields(MintBean $child_bean, array $parent_related_ids): void
    {
        $module = $this->bean->module_dir;
        if (!array_key_exists($module, static::RELATIONSHIP_LINKS_TO_COPY)) {
            return;
        }
        foreach (static::RELATIONSHIP_LINKS_TO_COPY[$module] as $field) {
            if (!$child_bean->load_relationship($field)) {
                continue;
            }
            $parent_ids = $parent_related_ids[$field] ?? [];
            $child_ids = $child_bean->$field->get();
            foreach ($child_ids as $child_rel_id) {
                if (!in_array($child_rel_id, $parent_ids)) {
                    $child_bean->$field->remove($child_rel_id);
                }
            }
            foreach ($parent_ids as $parent_id) {
                if (!in_array($parent_id, $child_ids)) {
                    $child_bean->$field->add($parent_id);
                }
            }
        }
    }

    protected function getIntervalForCustom(): DateInterval
    {
        if (empty($this->bean->repeat_interval) || empty($this->bean->repeat_interval_unit)) {
            return new DateInterval("P7D");
        }

        $unit = $this->bean->repeat_interval_unit;
        $interval_value = $this->bean->repeat_interval;

        switch ($unit) {
            case 'day':
                return new DateInterval("P{$interval_value}D");
            case 'week':
                return new DateInterval("P" . ($interval_value * 7) . "D");
            case 'month':
                return new DateInterval("P{$interval_value}M");
            case 'year':
                return new DateInterval("P{$interval_value}Y");
            default:
                return new DateInterval("P7D");
        }
    }

    protected function formatDateToDb(DateTime $date): string
    {
        return $date->format($this->timedate->get_db_date_time_format());
    }
}
