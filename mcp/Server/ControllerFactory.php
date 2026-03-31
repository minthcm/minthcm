<?php

namespace MintMCP\Server;

use Doctrine\ORM\EntityManagerInterface;

class ControllerFactory
{
    private static $instance = null;
    private DoctrineService $doctrineService;

    private function __construct(DoctrineService $doctrineService)
    {
        $this->doctrineService = $doctrineService;
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self(DoctrineService::getInstance());
        }
        return self::$instance;
    }

    /**
     * Create an instance of an API Controller
     *
     * @param string $controllerClass The fully qualified class name of the controller
     * @return mixed The controller instance
     */
    public function createController(string $controllerClass)
    {
        $entityManager = $this->doctrineService->getEntityManager();
        $constructor = (new \ReflectionClass($controllerClass))->getConstructor();

        if (
            $constructor &&
            ($param = $constructor->getParameters()[0] ?? null) &&
            ($type = $param->getType()) instanceof \ReflectionNamedType &&
            !$type->isBuiltin() &&
            is_a($type->getName(), EntityManagerInterface::class, true)
        ) {
            return new $controllerClass($entityManager);
        }

        return new $controllerClass();
    }
}
