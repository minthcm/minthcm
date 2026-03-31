<?php

namespace MintHCM\Data\ORM\Doctrine\MintEventManager\Subscribers;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use MintHCM\Data\ORM\Doctrine\MintEntity\MintEntity;

class CustomEntitySubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            Events::preUpdate,
            Events::prePersist,
        );
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getObject();
        $entity_manager = $args->getObjectManager();

        if ($entity instanceof MintEntity && $entity->hasCustomEntity()) {
            $entity_manager->persist($entity->custom_entity);
        }
    }

    public function prePersist(PrePersistEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof MintEntity && $entity->isCustomEntity()) {
            $entity->id = $entity->main_entity->id;
        }

    }
}
