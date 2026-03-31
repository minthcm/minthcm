<?php

namespace MintHCM\Data\ORM\Doctrine\MintRepository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMInvalidArgumentException;
use MintHCM\Data\ORM\Doctrine\MintEntity\MintEntity;
use MintHCM\Data\ORM\Doctrine\MintRepository\Traits\EntitiesListTrait;
use MintHCM\Data\ORM\Doctrine\MintRepository\Traits\LegacyActionsTrait;

class MintEntityRepository extends EntityRepository
{
    use LegacyActionsTrait;
    use EntitiesListTrait;

    public function getNewEntity()
    {
        $entityClass = $this->getClassName();
        return new $entityClass();
    }

    public function getEntityShortName(): string
    {
        $entityClass = $this->getClassName();
        return (new \ReflectionClass($entityClass))->getShortName();
    }

    /**
     * Save the entity 
     *
     * @param object $entity The entity to save
     * @param bool $should_flush Whether to flush changes to the database immediately
     * @return void
     */
    public function save($entity, bool $should_flush = false): void
    {
        if (! $entity instanceof MintEntity) {
            throw ORMInvalidArgumentException::invalidObject('MintEntityRepository#save()', $entity);
        }

        $this->_em->persist($entity);

        if ($entity->hasLegacyActions()) {
            $this->legacySave($entity);
            return;
        }
        if ($should_flush) {
            $this->_em->flush();
        }
    }

    /**
     * Delete the entity
     *
     * @param object $entity The entity to delete
     * @param bool $should_flush Whether to flush changes to the database immediately
     * @return void
     */
    public function delete($entity, bool $should_flush = false): void
    {
        if (! $entity instanceof MintEntity) {
            throw ORMInvalidArgumentException::invalidObject('MintEntityRepository#delete()', $entity);
        }

        if ($entity->hasLegacyActions()) {
            $this->legacyDelete($entity);
            return;
        }

        $this->_em->remove($entity);
        if ($should_flush) {
            $this->_em->flush();
        }
    }

    /**
     * Find an entity by its ID, including related fields
     *
     * @param string $id The ID of the entity to find
     * @param bool $with_deleted Whether to include deleted entities
     * @return MintEntity|null The found entity or null if not found
     */
    public function findWithRelatedFields(string $id, bool $with_deleted = false): ?MintEntity
    {
        /** @var MintEntity */
        $new_entity = $this->getNewEntity();
        $related_properties =  $new_entity->getRelatedPropertiesDefs();

        $query = $this->createQueryBuilder('e')
            ->where('e.id = :id')
            ->setParameter('id', $id);

        if (!$with_deleted) {
            $query->andWhere('e.deleted = false');
        }

        foreach ($related_properties as $related_defs) {
            $related_repository = $this->getEntityManager()->getRepository($related_defs['related_entity']);
            $related_entity = $related_repository->getNewEntity();
            if (!$with_deleted && property_exists($related_entity, 'deleted')) {
                $query->leftJoin('e.' . $related_defs['name'], $related_defs['name'], 'WITH', $related_defs['name'] . '.deleted = false');
            } else {
                $query->leftJoin('e.' . $related_defs['name'], $related_defs['name']);
            }
            $query->addSelect($related_defs['name']);
        }

        return $query->getQuery()->getOneOrNullResult();
    }
}
