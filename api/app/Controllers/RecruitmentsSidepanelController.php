<?php

namespace MintHCM\Api\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Api\Entities\Candidatures;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class RecruitmentsSidepanelController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function getCandidatureStatusCounts(Request $request, Response $response, array $args): Response
    {
        $recruitmentId = $args['id'];

        $rows = $this->entityManager->createQueryBuilder()
            ->select('c.status, COUNT(c.id) as cnt')
            ->from(Candidatures::class, 'c')
            ->where('c.recruitment_id = :recruitmentId')
            ->andWhere('c.deleted = :deleted')
            ->groupBy('c.status')
            ->orderBy('c.status', 'ASC')
            ->setParameter('recruitmentId', $recruitmentId)
            ->setParameter('deleted', false)
            ->getQuery()
            ->getArrayResult();

        $statusCounts = array_map(fn($row) => [
            'status' => $row['status'] ?? '',
            'count' => (int) $row['cnt'],
        ], $rows);

        $response = $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        $response->getBody()->write(json_encode($statusCounts));
        return $response;
    }
}
