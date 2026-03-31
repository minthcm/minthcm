<?php

namespace MintHCM\Data\ORM\Doctrine\MintRepository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Repository\DefaultRepositoryFactory;
use Doctrine\ORM\Repository\RepositoryFactory;

class MintRepositoryFactory implements RepositoryFactory
{
    private const ENTITY_NAMESPACES = [
        'MintHCM\Api\Entities',
        'MintHCM\Custom\Api\Entities',
    ];

    private DefaultRepositoryFactory $defaultFactory;

    public function __construct()
    {
        $this->defaultFactory = new DefaultRepositoryFactory();
    }

    public function getRepository(EntityManagerInterface $entityManager, $entityName)
    {
        if (!class_exists($entityName) || !is_subclass_of($entityName, EntityRepository::class)) {
            foreach (self::ENTITY_NAMESPACES as $namespace) {
                $fullClassName = $namespace . '\\' . $entityName;
                if (class_exists($fullClassName)) {
                    $entityName = $fullClassName;
                    break;
                }
            }
        }
        
        return $this->defaultFactory->getRepository($entityManager, $entityName);
    }
}