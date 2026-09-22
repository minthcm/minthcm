<?php

namespace MintHCM\Lib\Services;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Api\Entities\NonWorkingDays;
use MintHCM\Api\Entities\WorkSchedules;

class LeaveService
{
    private const LEAVE_TYPES = ['holiday', 'leave_at_request'];

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function getUpcomingLeave(string $employeeId): array
    {
        $now = new \DateTime('today');
        $rangeEnd = (clone $now)->modify('+2 months');

        $entries = $this->entityManager->createQueryBuilder()
            ->select('w')
            ->from(WorkSchedules::class, 'w')
            ->where('w.assigned_user_id = :userId')
            ->andWhere('w.type IN (:types)')
            ->andWhere('w.deleted = :deleted')
            ->andWhere('w.supervisor_acceptance IS NULL OR w.supervisor_acceptance != :rejected')
            ->andWhere('w.date_start >= :now')
            ->andWhere('w.date_start <= :rangeEnd')
            ->orderBy('w.schedule_date', 'ASC')
            ->setParameter('userId', $employeeId)
            ->setParameter('types', self::LEAVE_TYPES)
            ->setParameter('deleted', false)
            ->setParameter('rejected', 'rejected')
            ->setParameter('now', $now)
            ->setParameter('rangeEnd', $rangeEnd)
            ->getQuery()
            ->getResult();

        $nonWorkingDays = $this->getNonWorkingDays($now, $rangeEnd);

        return $this->groupConsecutiveLeave($entries, $nonWorkingDays);
    }

    /**
     * Merges consecutive same-type leave entries into date-range groups, treating gaps that
     * consist entirely of weekends/company non-working days as a continuation of the same leave.
     */
    private function groupConsecutiveLeave(array $entries, array $nonWorkingDays): array
    {
        $groups = [];
        $current = null;

        foreach ($entries as $entry) {
            if (
                $current !== null
                && $current['type'] === $entry->type
                && $this->isBridgeableGap($current['lastDate'], $entry->schedule_date, $nonWorkingDays)
            ) {
                $current['entries'][] = $entry;
                $current['lastDate'] = $entry->schedule_date;
            } else {
                if ($current !== null) {
                    $groups[] = $current;
                }
                $current = ['type' => $entry->type, 'entries' => [$entry], 'lastDate' => $entry->schedule_date];
            }
        }
        if ($current !== null) {
            $groups[] = $current;
        }

        return array_map(fn(array $group) => $this->mapGroupToLeaveEntry($group), $groups);
    }

    private function mapGroupToLeaveEntry(array $group): array
    {
        $groupEntries = $group['entries'];
        $first = $groupEntries[0];
        $last = $groupEntries[count($groupEntries) - 1];
        $allAccepted = true;
        foreach ($groupEntries as $entry) {
            if ($entry->supervisor_acceptance !== 'accepted') {
                $allAccepted = false;
                break;
            }
        }

        return [
            'id' => $first->id,
            'type' => $group['type'],
            'date_start' => $first->date_start?->format(\DateTime::ATOM),
            'date_end' => $last->date_end?->format(\DateTime::ATOM),
            'supervisor_acceptance' => $allAccepted ? 'accepted' : 'wait',
        ];
    }

    /**
     * True when every calendar day strictly between the two dates is a weekend or a
     * company non-working day, i.e. the gap doesn't represent an actual working day off-leave.
     */
    private function isBridgeableGap(\DateTime $previousDate, \DateTime $nextDate, array $nonWorkingDays): bool
    {
        $cursor = (clone $previousDate)->modify('+1 day');

        while ($cursor < $nextDate) {
            $dayOfWeek = (int) $cursor->format('N');
            if ($dayOfWeek < 6 && !in_array($cursor->format('Y-m-d'), $nonWorkingDays, true)) {
                return false;
            }
            $cursor->modify('+1 day');
        }

        return true;
    }

    private function getNonWorkingDays(\DateTime $from, \DateTime $to): array
    {
        $rows = $this->entityManager->createQueryBuilder()
            ->select('n.date')
            ->from(NonWorkingDays::class, 'n')
            ->where('n.deleted = :deleted')
            ->andWhere('n.date >= :from')
            ->andWhere('n.date <= :to')
            ->setParameter('deleted', false)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()
            ->getArrayResult();

        return array_map(static fn(array $row) => $row['date']->format('Y-m-d'), $rows);
    }
}
