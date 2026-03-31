<?php


namespace MintHCM\Api\Repositories;

use MintHCM\Data\ORM\Doctrine\MintRepository\MintEntityRepository;

class PositionsRepository extends MintEntityRepository
{
    public function getCompetencies($positionId)
    {
        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.competencyratings', 'cr', 'WITH', 'cr.deleted = 0')
            ->leftJoin('cr.competencies', 'c', 'WITH', 'c.deleted = 0')
            ->where('p.id = :positionId')
            ->setParameter('positionId', $positionId)
            ->select('c.id, c.name, c.description');

        return $qb->getQuery()->getResult();
    }

    public function getResponsibilities($positionId)
    {
        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.responsibilities', 'r', 'WITH', 'r.deleted = 0')
            ->where('p.id = :positionId')
            ->setParameter('positionId', $positionId)
            ->select('r.id, r.name, r.description');

        return $qb->getQuery()->getResult();
    }
}