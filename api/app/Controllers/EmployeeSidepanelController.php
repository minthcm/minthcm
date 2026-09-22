<?php

namespace MintHCM\Api\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Api\Entities\CompetencyRatings;
use MintHCM\Api\Entities\Competencies;
use MintHCM\Api\Entities\PeriodsOfEmployment;
use MintHCM\Api\Entities\Trainings;
use MintHCM\Lib\Services\KudosSummaryService;
use MintHCM\Lib\Services\LeaveService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class EmployeeSidepanelController
{
    private const RATING_MAX = 5;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private LeaveService $leaveService,
        private KudosSummaryService $kudosSummaryService,
    ) {
    }

    /**
     * Mirrors the ACL check used by KudosController/CommentsController/FilesController:
     * loads the target Employee bean and verifies the current user's view access.
     */
    private function checkEmployeeAccess(string $employeeId): ?Response
    {
        chdir('../legacy');
        $employee = \BeanFactory::getBean('Employees', $employeeId);
        $notFound = empty($employee->id);
        $forbidden = !$notFound && !$employee->ACLAccess('view');
        chdir('../api');

        if ($notFound) {
            return (new Response())->withStatus(404);
        }
        if ($forbidden) {
            return (new Response())->withStatus(403);
        }

        return null;
    }

    public function getUpcomingLeave(Request $request, Response $response, array $args): Response
    {
        $employeeId = $args['id'];
        if ($accessError = $this->checkEmployeeAccess($employeeId)) {
            return $accessError;
        }

        $data = $this->leaveService->getUpcomingLeave($employeeId);

        $response = $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    public function getTrainingsSummary(Request $request, Response $response, array $args): Response
    {
        $userId = $args['id'];
        if ($accessError = $this->checkEmployeeAccess($userId)) {
            return $accessError;
        }

        $year = (int) date('Y');
        $yearStart = new \DateTime("{$year}-01-01");
        $yearEnd = new \DateTime(($year + 1) . '-01-01');

        $rows = $this->entityManager->createQueryBuilder()
            ->select('t.status, COUNT(t.id) as cnt')
            ->from(Trainings::class, 't')
            ->where('t.assigned_user_id = :userId')
            ->andWhere('t.deleted = :deleted')
            ->andWhere('t.date_start >= :yearStart')
            ->andWhere('t.date_start < :yearEnd')
            ->groupBy('t.status')
            ->setParameter('userId', $userId)
            ->setParameter('deleted', false)
            ->setParameter('yearStart', $yearStart)
            ->setParameter('yearEnd', $yearEnd)
            ->getQuery()
            ->getArrayResult();

        $counts = array_column($rows, 'cnt', 'status');
        $held = (int) ($counts['held'] ?? 0);
        $planned = (int) ($counts['planned'] ?? 0);
        $notHeld = (int) ($counts['not_held'] ?? 0);

        $data = [
            'held' => $held,
            'planned' => $planned,
            'not_held' => $notHeld,
            'total' => $held + $planned + $notHeld,
            'year' => $year,
        ];

        $response = $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    public function getCompetenciesSummary(Request $request, Response $response, array $args): Response
    {
        $userId = $args['id'];
        if ($accessError = $this->checkEmployeeAccess($userId)) {
            return $accessError;
        }

        $rows = $this->entityManager->createQueryBuilder()
            ->select('c.name, cr.rating')
            ->from(CompetencyRatings::class, 'cr')
            ->join(Competencies::class, 'c', 'WITH', 'cr.competency_id = c.id')
            ->where('cr.employee_id = :userId')
            ->andWhere('cr.deleted = :deleted')
            ->andWhere('c.deleted = :deleted')
            ->andWhere('cr.rating IN (:validRatings)')
            ->setParameter('userId', $userId)
            ->setParameter('deleted', false)
            ->setParameter('validRatings', [1, 2, 3, 4, 5])
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getArrayResult();

        $competencies = array_map(fn($row) => [
            'name' => $row['name'],
            'score' => (int) $row['rating'],
            'max' => self::RATING_MAX,
        ], $rows);

        $response = $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        $response->getBody()->write(json_encode($competencies));
        return $response;
    }

    public function getKudosSummary(Request $request, Response $response, array $args): Response
    {
        $userId = $args['id'];
        if ($accessError = $this->checkEmployeeAccess($userId)) {
            return $accessError;
        }

        $data = $this->kudosSummaryService->getKudosSummary($userId);

        $response = $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    public function getTenureSummary(Request $request, Response $response, array $args): Response
    {
        $employeeId = $args['id'];
        if ($accessError = $this->checkEmployeeAccess($employeeId)) {
            return $accessError;
        }

        $now = new \DateTime();

        $period = $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from(PeriodsOfEmployment::class, 'p')
            ->where('p.employee_id = :employeeId')
            ->andWhere('p.deleted = :deleted')
            ->andWhere('p.period_starting_date <= :now')
            ->andWhere('p.period_ending_date IS NULL OR p.period_ending_date >= :now')
            ->orderBy('p.period_starting_date', 'DESC')
            ->setMaxResults(1)
            ->setParameter('employeeId', $employeeId)
            ->setParameter('deleted', false)
            ->setParameter('now', $now)
            ->getQuery()
            ->getOneOrNullResult();

        $data = [
            'period_starting_date' => $period?->period_starting_date?->format(\DateTime::ATOM),
            'period_ending_date'   => $period?->period_ending_date?->format(\DateTime::ATOM),
        ];

        $response = $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        $response->getBody()->write(json_encode($data));
        return $response;
    }
}
