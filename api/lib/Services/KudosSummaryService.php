<?php

namespace MintHCM\Lib\Services;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Api\Entities\Kudos;
use MintHCM\Api\Entities\Users;

class KudosSummaryService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function getKudosSummary(string $userId): array
    {
        $year = (int) date('Y');
        $yearStart = new \DateTime("{$year}-01-01");
        $yearEnd = new \DateTime(($year + 1) . '-01-01');

        return [
            'total' => $this->getTotalKudosCount($userId),
            'thisYear' => $this->getKudosCountForYear($userId, $yearStart, $yearEnd),
            'lastGiver' => $this->getLastKudoGiverName($userId, $yearStart, $yearEnd),
        ];
    }

    private function getTotalKudosCount(string $userId): int
    {
        return (int) $this->entityManager->createQueryBuilder()
            ->select('COUNT(k.id)')
            ->from(Kudos::class, 'k')
            ->where('k.employee_id = :userId')
            ->andWhere('k.deleted = :deleted')
            ->setParameter('userId', $userId)
            ->setParameter('deleted', false)
            ->getQuery()
            ->getSingleScalarResult();
    }

    private function getKudosCountForYear(string $userId, \DateTime $yearStart, \DateTime $yearEnd): int
    {
        return (int) $this->entityManager->createQueryBuilder()
            ->select('COUNT(k.id)')
            ->from(Kudos::class, 'k')
            ->where('k.employee_id = :userId')
            ->andWhere('k.deleted = :deleted')
            ->andWhere('k.date_entered >= :yearStart')
            ->andWhere('k.date_entered < :yearEnd')
            ->setParameter('userId', $userId)
            ->setParameter('deleted', false)
            ->setParameter('yearStart', $yearStart)
            ->setParameter('yearEnd', $yearEnd)
            ->getQuery()
            ->getSingleScalarResult();
    }

    private function getLastKudoGiverName(string $userId, \DateTime $yearStart, \DateTime $yearEnd): string
    {
        $lastKudo = $this->entityManager->createQueryBuilder()
            ->select('k.created_by')
            ->from(Kudos::class, 'k')
            ->where('k.employee_id = :userId')
            ->andWhere('k.deleted = :deleted')
            ->andWhere('k.date_entered >= :yearStart')
            ->andWhere('k.date_entered < :yearEnd')
            ->orderBy('k.date_entered', 'DESC')
            ->setMaxResults(1)
            ->setParameter('userId', $userId)
            ->setParameter('deleted', false)
            ->setParameter('yearStart', $yearStart)
            ->setParameter('yearEnd', $yearEnd)
            ->getQuery()
            ->getOneOrNullResult();

        $lastGiverId = $lastKudo['created_by'] ?? null;
        if (!$lastGiverId) {
            return '';
        }

        $giver = $this->entityManager->createQueryBuilder()
            ->select('u.first_name, u.last_name')
            ->from(Users::class, 'u')
            ->where('u.id = :id')
            ->setParameter('id', $lastGiverId)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$giver) {
            return '';
        }

        return trim(($giver['first_name'] ?? '') . ' ' . ($giver['last_name'] ?? ''));
    }
}
