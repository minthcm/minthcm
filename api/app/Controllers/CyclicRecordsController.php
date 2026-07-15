<?php

namespace MintHCM\Api\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Data\BeanFactory;
use MintHCM\Utils\CyclicRecordsSaver;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

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
        $isCyclicRecord = $CRS->isCyclicRecord();
        $hasCyclicRecords = $CRS->hasCyclicRecords();
        $canEdit = !$isCyclicRecord && !$hasCyclicRecords;

        $response = $response->withStatus(200);
        $response->getBody()->write(json_encode([
            'canEdit' => $canEdit,
            'isCyclicRecord' => $isCyclicRecord,
            'hasCyclicRecords' => $hasCyclicRecords,
        ]));
        return $response;
    }

    /**
     * Return the full list of cyclic records that would be created for a given
     * parent record, without actually creating any.  The response contains the
     * exact {date_start, date_end} pairs so the frontend can split them into
     * chunks and POST them back via createCyclicRecordsBatch().
     */
    public function planCyclicRecords(Request $request, Response $response, array $args): Response
    {
        $module = $request->getAttribute('module');
        $id = $request->getAttribute('id');

        $bean = BeanFactory::getBean($module, $id);
        if (empty($bean->id)) {
            $response->getBody()->write(json_encode(['error' => 'Record not found']));
            return $response->withStatus(404);
        }

        $CRS = new CyclicRecordsSaver($bean, $this->entityManager);
        try {
            $records = $CRS->plan();
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withStatus(422);
        }

        $response->getBody()->write(json_encode([
            'records'     => $records,
            'total'       => count($records),
            'related_ids' => $CRS->getRelatedIds(),
        ]));
        return $response->withStatus(200);
    }

    /**
     * Create a batch of cyclic records from a list of pre-calculated
     * {date_start, date_end} pairs.  Called repeatedly by the frontend
     * in chunks of ~10 records while the progress bar is displayed.
     */
    public function createCyclicRecordsBatch(Request $request, Response $response, array $args): Response
    {
        $module = $request->getAttribute('module');
        $id = $request->getAttribute('id');

        $bean = BeanFactory::getBean($module, $id);
        if (empty($bean->id)) {
            $response->getBody()->write(json_encode(['error' => 'Record not found']));
            return $response->withStatus(404);
        }

        if (!$bean->ACLAccess('edit')) {
            $response->getBody()->write(json_encode(['error' => 'Access denied']));
            return $response->withStatus(403);
        }

        $records = $request->getAttribute('records') ?? [];

        if (!is_array($records) || empty($records)) {
            $response->getBody()->write(json_encode(['error' => 'No records provided']));
            return $response->withStatus(422);
        }

        $related_ids = $request->getAttribute('related_ids') ?? [];

        $CRS = new CyclicRecordsSaver($bean, $this->entityManager);
        $created = $CRS->createFromPlan($records, $related_ids);

        $response->getBody()->write(json_encode(['created' => $created]));
        return $response->withStatus(200);
    }

    /**
     * Return the IDs of all existing cyclic children of a given parent record
     * so the frontend can split them into chunks and POST them back via
     * updateCyclicRecordsBatch().
     */
    public function planCyclicRecordsUpdate(Request $request, Response $response, array $args): Response
    {
        $module = $request->getAttribute('module');
        $id = $request->getAttribute('id');

        $bean = BeanFactory::getBean($module, $id);
        if (empty($bean->id)) {
            $response->getBody()->write(json_encode(['error' => 'Record not found']));
            return $response->withStatus(404);
        }

        $CRS = new CyclicRecordsSaver($bean, $this->entityManager);
        $ids = $CRS->planUpdate();

        $response->getBody()->write(json_encode([
            'ids' => $ids,
            'total' => count($ids),
        ]));
        return $response->withStatus(200);
    }

    /**
     * Propagate the current state of the parent bean onto a batch of cyclic
     * children identified by their IDs.  Called repeatedly by the frontend
     * in chunks while the progress bar is displayed.
     */
    public function updateCyclicRecordsBatch(Request $request, Response $response, array $args): Response
    {
        $module = $request->getAttribute('module');
        $id = $request->getAttribute('id');

        $bean = BeanFactory::getBean($module, $id);
        if (empty($bean->id)) {
            $response->getBody()->write(json_encode(['error' => 'Record not found']));
            return $response->withStatus(404);
        }

        $ids = $request->getAttribute('ids') ?? [];

        if (!is_array($ids) || empty($ids)) {
            $response->getBody()->write(json_encode(['error' => 'No ids provided']));
            return $response->withStatus(422);
        }

        $CRS = new CyclicRecordsSaver($bean, $this->entityManager);
        $updated = $CRS->updateFromPlan($ids);

        $response->getBody()->write(json_encode(['updated' => $updated]));
        return $response->withStatus(200);
    }
}
