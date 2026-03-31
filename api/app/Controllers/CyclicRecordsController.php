<?php

namespace MintHCM\Api\Controllers;

use MintHCM\Utils\CyclicRecordsSaver;
use MintHCM\Data\BeanFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;
use Doctrine\ORM\EntityManagerInterface;

class CyclicRecordsController
{
    public function __construct(protected EntityManagerInterface $entityManager)
    {
    }

    public function recordCanBeRepeated(Request $request, Response $response, array $args): Response
    {
        $module = $request->getAttribute('module');
        $id = $request->getAttribute('id');

        $bean = BeanFactory::getBean($module, $id);
        if (empty($bean->id)) {
            $response = $response->withStatus(404);
            $response->getBody()->write(json_encode(['canEdit' => false, 'error' => 'Record not found']));
            return $response;
        }
        $CRS = new CyclicRecordsSaver($bean, $this->entityManager);
        $canEdit = !$CRS->isCyclicRecord() && !$CRS->hasCyclicRecords();

        $response = $response->withStatus(200);
        $response->getBody()->write(json_encode(['canEdit' => $canEdit]));
        return $response;
    }
}