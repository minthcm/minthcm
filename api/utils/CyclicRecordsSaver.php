<?php

namespace MintHCM\Utils;

use DateInterval;
use DateTime;
use MintHCM\Data\BeanFactory;
use MintHCM\Data\MintBean;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class CyclicRecordsSaver
{
    const REPEAT_LIMIT = 250;

    const ALLOWED_REPEAT_TYPES = [
        'Daily',
        'Weekly',
        'Monthly',
        'Yearly',
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

    const SKIP_FIELDS = [
        'id',
        'date_entered',
        'date_modified',
        'date_indexed',
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

    public function __construct(
        protected $bean,
        protected EntityManagerInterface $entityManager
    ) {}

    public function run(): void
    {
        if (
            empty($this->bean->id)
            || empty($this->bean->repeat_type)
            || $this->hasCyclicRecords()
            || $this->isCyclicRecord()
        ) {
            return;
        }
        $this->validate();
        $this->saveCyclicRecords();
    }

    public function isCyclicRecord(): bool
    {
        return !empty($this->bean->repeat_parent_id);
    }

    public function hasCyclicRecords(): bool
    {
        $entity_class = $this->getEntityClassName();
        $queryBuilder = $this->entityManager->createQueryBuilder($entity_class);
        $children = $queryBuilder->select('e.id')
            ->from($entity_class, 'e')
            ->where('e.repeat_parent_id = :parentId')
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
        $date_start_object = new DateTime($this->bean->date_start);
        if (empty($date_start_object)) {
            throw new Exception("Cannot parse date_start for bean ID {$this->bean->id}");
        }
        $date_end_object = new DateTime($this->bean->date_end);
        if (empty($date_end_object)) {
            throw new Exception("Cannot parse date_end for bean ID {$this->bean->id}");
        }
        if ($date_end_object < $date_start_object) {
            throw new Exception("date_end cannot be before date_start for bean ID {$this->bean->id}");
        }
        if (!in_array($this->bean->repeat_type, static::ALLOWED_REPEAT_TYPES)) {
            throw new Exception("Invalid repeat_type for bean ID {$this->bean->id}");
        }
        if ($this->bean->repeat_type === 'Weekly') {
            if (empty($this->bean->repeat_dow)) {
                throw new Exception("repeat_dow field is required for Weekly repeat_type for bean ID {$this->bean->id}");
            }
            $number_days_of_week = str_split($this->bean->repeat_dow);
            if (!empty(array_diff($number_days_of_week, array_keys(static::ALLOWED_WEEK_DAYS)))) {
                throw new Exception("Invalid repeat_dow for bean ID {$this->bean->id}");
            }
        }
        if (
            empty($this->bean->repeat_interval)
            || (int) $this->bean->repeat_interval <= 0
        ) {
            throw new Exception("Invalid repeat_interval for bean ID {$this->bean->id}");
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
                new DateTime($this->bean->repeat_until) < new DateTime($this->bean->date_start)
                || new DateTime($this->bean->repeat_until) < new DateTime($this->bean->date_end)
            )
        ) {
            throw new Exception("Invalid repeat_until for bean ID {$this->bean->id}");
        }
    }

    protected function saveCyclicRecords(): void
    {
        $date_start_object = new DateTime($this->bean->date_start);
        $date_end_object = new DateTime($this->bean->date_end);
        $dates_diff = $date_start_object->diff($date_end_object);
        $next_start_date_objects = $this->calculateStartDates(clone $date_start_object);
        if (count($next_start_date_objects) > static::REPEAT_LIMIT) {
            $next_start_date_objects = array_slice($next_start_date_objects, 0, static::REPEAT_LIMIT);
        }
        foreach ($next_start_date_objects as $next_start_date_object) {
            $next_start_date_object->setTime(
                $date_start_object->format('H'),
                $date_start_object->format('i'),
                $date_start_object->format('s'),
            );
            $next_end_date_object = clone $next_start_date_object;
            $next_end_date_object->add($dates_diff);
            $this->createCyclicRecord($next_start_date_object, $next_end_date_object);
        }
    }

    protected function createCyclicRecord(DateTime $next_start_date_object, DateTime $next_end_date_object): void
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
        $this->copyRelationshipFields($new_bean);
    }

    protected function calculateStartDates(DateTime $start_date_object): array
    {
        if ($this->bean->repeat_type === 'Weekly') {
            return $this->calculateWeeklyStartDates($start_date_object);
        }
        return $this->calculateIntervalStartDates($start_date_object);
    }

    protected function calculateWeeklyStartDates(DateTime $start_date_object): array
    {
        if (!empty($this->bean->repeat_count)) {
            return $this->calculateWeeklyStartDatesByCount(
                $start_date_object,
                (int) $this->bean->repeat_count
            );
        }
        return $this->calculateWeeklyStartDatesByUntil(
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

    protected function calculateWeeklyStartDatesByCount(DateTime $start_date_object, int $count): array
    {
        $start_date_objects = [];
        $date_interval = $this->getDateRepeatInterval();
        $expected_days_of_week = $this->getRepeatDOWNames();
        $count--;
        $current_week_start_date_object = (clone $start_date_object)->modify('monday this week')->modify('-1 day');
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

    protected function calculateWeeklyStartDatesByUntil(DateTime $start_date_object, DateTime $until_date_object): array
    {
        $start_date_objects = [];
        $date_interval = $this->getDateRepeatInterval();
        $expected_days_of_week = $this->getRepeatDOWNames();
        $current_week_start_date_object = (clone $start_date_object)->modify('monday this week')->modify('-1 day');
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
        if ($this->bean->repeat_type !== 'Weekly') {
            return array_values(static::ALLOWED_WEEK_DAYS);
        }
        $days_of_week_numbers = array_unique(str_split($this->bean->repeat_dow));
        sort($days_of_week_numbers);
        $days_of_week_names = [];
        foreach ($days_of_week_numbers as $day_of_week_number) {
            $days_of_week_names[] = static::ALLOWED_WEEK_DAYS[(int) $day_of_week_number];
        }
        return $days_of_week_names;
    }

    protected function getDateRepeatInterval(): ?DateInterval
    {
        $repeat_interval = (int) $this->bean->repeat_interval;
        switch ($this->bean->repeat_type) {
            case 'Daily':
                return new DateInterval("P{$repeat_interval}D");
            case 'Weekly':
                return new DateInterval("P{$repeat_interval}W");
            case 'Monthly':
                return new DateInterval("P{$repeat_interval}M");
            case 'Yearly':
                return new DateInterval("P{$repeat_interval}Y");
        }
        return null;
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

    protected function copyRelationshipFields(MintBean $new_bean): void
    {
        $module = $this->bean->module_dir;
        if (!array_key_exists($module, static::RELATIONSHIP_LINKS_TO_COPY)) {
            return;
        }

        $links_to_copy = static::RELATIONSHIP_LINKS_TO_COPY[$module];
        foreach ($links_to_copy as $field) {
            $related_beans = $this->bean->get_linked_beans($field);
            if ($new_bean->load_relationship($field)) {
                foreach ($related_beans as $related_bean) {
                    $new_bean->$field->add($related_bean->id);
                }
            }
        }
    }
}
