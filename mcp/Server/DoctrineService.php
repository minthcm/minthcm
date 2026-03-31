<?php

namespace MintMCP\Server;

use MintHCM\Api\Containers\Doctrine\DoctrineContainerBuilder;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineService
{
    private static $instance = null;
    private $entityManager = null;

    private function __construct()
    {
        $doctrineContainerBuilder = new DoctrineContainerBuilder();
        $container = $doctrineContainerBuilder->build();
        $this->entityManager = $container->get(EntityManagerInterface::class);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}