<?php


namespace MintHCM\Api\Repositories;

use MintHCM\Data\ORM\Doctrine\MintRepository\MintEntityRepository;

class EmployeeRolesRepository extends MintEntityRepository
{
    public function getCompetencies($roleId)
    {
        $qb = $this->createQueryBuilder('er')
            ->innerJoin('er.competencyratings', 'cr', 'WITH', 'cr.deleted = 0')
            ->leftJoin('cr.competencies', 'c', 'WITH', 'c.deleted = 0')
            ->where('er.id = :roleId')
            ->setParameter('roleId', $roleId)
            ->select('c.id, c.name, c.description');

        return $qb->getQuery()->getResult();
    }

    public function getResponsibilities($roleId)
    {
        $qb = $this->createQueryBuilder('er')
            ->innerJoin('er.responsibilities', 'rr', 'WITH', 'rr.deleted = 0')
            ->where('er.id = :roleId')
            ->setParameter('roleId', $roleId)
            ->select('rr.id, rr.name, rr.description');

        return $qb->getQuery()->getResult();
    }
}