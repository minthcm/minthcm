<?php

namespace MintHCM\Api\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;
use Doctrine\ORM\EntityManagerInterface;

#[\AllowDynamicProperties]
class PositionsController
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getCompetencies(Request $request, Response $response, array $args): Response
    {
        $module = $request->getAttribute('module');
        $recordId = $request->getAttribute('record_id');
        $response = $response->withHeader('Content-type', 'application/json');
        $competencies = [];
        $entity = $this->getEntity($module);
        $competencies = $this->entityManager->getRepository($entity::class)->getCompetencies($recordId);
        $response = $response->withStatus(200);
        $response->getBody()->write(json_encode($competencies));
        return $response;
    }

    public function getResponsibilities(Request $request, Response $response, array $args): Response
    {
        $module = $request->getAttribute('module');
        $recordId = $request->getAttribute('record_id');
        $response = $response->withHeader('Content-type', 'application/json');
        $responsibilities = [];
        $entity = $this->getEntity($module);
        $responsibilities = $this->entityManager->getRepository($entity::class)->getResponsibilities($recordId);
        $response = $response->withStatus(200);
        $response->getBody()->write(json_encode($responsibilities));
        return $response;
    }

    private function getEntity(string $entity_name)
    {
        $class = "MintHCM\\Api\\Entities\\$entity_name";
        if (! class_exists($class)) {
            throw new \Exception("Entity class $class does not exist");
        }
        return new $class();
    }

}