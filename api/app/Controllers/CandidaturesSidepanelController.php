<?php

namespace MintHCM\Api\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Api\Entities\AppraisalItems;
use MintHCM\Api\Entities\Appraisals;
use MintHCM\Api\Entities\Candidatures;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class CandidaturesSidepanelController
{
    private const SCORE_MAX = 5;

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * Mirrors the ACL check used by EmployeeSidepanelController/KudosController/CommentsController/FilesController:
     * loads the target Candidature bean and verifies the current user's view access.
     */
    private function checkCandidatureAccess(string $candidatureId): ?Response
    {
        chdir('../legacy');
        $candidature = \BeanFactory::getBean('Candidatures', $candidatureId);
        $notFound = empty($candidature->id);
        $forbidden = !$notFound && !$candidature->ACLAccess('view');
        chdir('../api');

        if ($notFound) {
            return (new Response())->withStatus(404);
        }
        if ($forbidden) {
            return (new Response())->withStatus(403);
        }

        return null;
    }

    public function getCandidateScore(Request $request, Response $response, array $args): Response
    {
        $candidatureId = $args['id'];
        if ($accessError = $this->checkCandidatureAccess($candidatureId)) {
            return $accessError;
        }

        $rows = $this->entityManager->createQueryBuilder()
            ->select('ai.value')
            ->from(AppraisalItems::class, 'ai')
            ->join(Appraisals::class, 'a', 'WITH', 'ai.appraisal_id = a.id')
            ->where('a.candidature_id = :candidatureId')
            ->andWhere('ai.deleted = :deleted')
            ->andWhere('a.deleted = :deleted')
            ->andWhere('ai.value IN (:validValues)')
            ->setParameter('candidatureId', $candidatureId)
            ->setParameter('deleted', false)
            ->setParameter('validValues', ['1', '2', '3', '4', '5'])
            ->getQuery()
            ->getArrayResult();

        $count = count($rows);
        $score = $count > 0
            ? round(array_sum(array_column($rows, 'value')) / $count, 2)
            : 0;

        $data = [
            'score' => $score,
            'max' => self::SCORE_MAX,
            'count' => $count,
        ];

        $response = $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    public function getApplicationHistory(Request $request, Response $response, array $args): Response
    {
        $candidatureId = $args['id'];
        if ($accessError = $this->checkCandidatureAccess($candidatureId)) {
            return $accessError;
        }

        $current = $this->entityManager->createQueryBuilder()
            ->select('c.parent_id, c.parent_type')
            ->from(Candidatures::class, 'c')
            ->where('c.id = :id')
            ->andWhere('c.deleted = :deleted')
            ->setParameter('id', $candidatureId)
            ->setParameter('deleted', false)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$current || empty($current['parent_id']) || $current['parent_type'] !== 'Candidates') {
            $response = $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            $response->getBody()->write(json_encode([]));
            return $response;
        }

        $rows = $this->entityManager->createQueryBuilder()
            ->select('c.id, c.name, c.status, c.date_entered')
            ->from(Candidatures::class, 'c')
            ->where('c.parent_id = :parentId')
            ->andWhere('c.parent_type = :parentType')
            ->andWhere('c.id != :currentId')
            ->andWhere('c.deleted = :deleted')
            ->orderBy('c.date_entered', 'DESC')
            ->setParameter('parentId', $current['parent_id'])
            ->setParameter('parentType', 'Candidates')
            ->setParameter('currentId', $candidatureId)
            ->setParameter('deleted', false)
            ->getQuery()
            ->getArrayResult();

        $history = array_map(fn($row) => [
            'id' => $row['id'],
            'name' => $row['name'],
            'status' => $row['status'] ?? '',
            'dateEntered' => $row['date_entered'] instanceof \DateTimeInterface
                ? $row['date_entered']->format('Y-m-d\TH:i:s\Z')
                : (string) $row['date_entered'],
        ], $rows);

        $response = $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        $response->getBody()->write(json_encode($history));
        return $response;
    }
}
