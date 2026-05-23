<?php

namespace MintHCM\Modules\WorkSchedules\api\controllers;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use MintHCM\Data\BeanFactory;
use MintHCM\Utils\LegacyConnector;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Psr7\Response;

class CloseWorkSchedule
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $record_id = $request->getAttribute('record');
        $result = false;

        if (empty($record_id)) {
            throw new HttpBadRequestException($request, 'Parameter "record" is missing or empty.');
        }
        try {
            $workschedule = BeanFactory::getBean('WorkSchedules', $record_id);
            if(empty($workschedule->id) || $workschedule->id !== $record_id){
                throw new Exception("WorkSchedules bean could not be found!");
            }
            $accept_workschedule_validator = new LegacyConnector('AcceptWorkScheduleValidator', 'modules/WorkSchedules/AcceptWorkScheduleValidator.php', [$workschedule]);
            $employee = BeanFactory::getBean('Employees', $workschedule->assigned_user_id);
            $accept_workschedule_validator->employee = $employee;
            $result = $accept_workschedule_validator->validate();
        } catch (Exception $e) {
            throw new HttpBadRequestException($request, 'Error while checking work schedule: ' . $e->getMessage());
        }
        $response->getBody()->write(json_encode(['result' => $result]));
        return $response;
    }

}
