<?php

namespace MintHCM\Data\ORM\Doctrine\MintRepository\Traits;

use Doctrine\ORM\ORMInvalidArgumentException;
use MintHCM\Data\ORM\Doctrine\MintEntity\MintEntity;

trait LegacyActionsTrait
{
    /**
     * Save the entity to the legacy SugarBean
     *
     * @param object $entity The entity to save
     * @param bool $check_notify 
     * @return bool True on success, false on failure
     */
    public function legacySave($entity, bool $check_notify = false): bool
    {
        if (! $entity instanceof MintEntity) {
            throw ORMInvalidArgumentException::invalidObject('MintEntityRepository#legacySave()', $entity);
        }

        $saved = $entity->legacySave($check_notify);
        if ($saved) {
            // Refresh and detach the entity to avoid query after flush
            $this->_em->refresh($entity);
            $this->_em->detach($entity);
            if ($entity->hasCustomEntity()) {
                $this->_em->refresh($entity->custom_entity);
                $this->_em->detach($entity->custom_entity);
            }
        }
        return $saved;
    }

    /**
     * Delete the entity from the legacy SugarBean
     *
     * @param object $entity The entity to delete
     * @return bool True on success, false on failure
     */
    public function legacyDelete($entity): bool
    {
        if (! $entity instanceof MintEntity) {
            throw ORMInvalidArgumentException::invalidObject('MintEntityRepository#legacyDelete()', $entity);
        }

        $deleted = $entity->legacyDelete();
        if ($deleted) {
            // Refresh and detach the entity to avoid query after flush
            $this->_em->persist($entity);
            $this->_em->refresh($entity);
            $this->_em->detach($entity);
        }
        return $deleted;
    }
}