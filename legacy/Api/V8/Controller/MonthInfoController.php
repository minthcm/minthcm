<?php

namespace Api\V8\Controller;

use Api\V8\Param\MonthInfoParams;
use Api\V8\Service\MonthInfoService;
use Exception;
use Slim\Http\Request;
use Slim\Http\Response;

class MonthInfoController extends BaseController
{
    protected $monthInfoService;

    public function __construct(MonthInfoService $monthInfoService)
    {
        $this->monthInfoService = $monthInfoService;
    }
    
    public function getMonthInfo(Request $request, Response $response, array $args, MonthInfoParams $params)
    {
        try {
            $jsonResponse = $this->monthInfoService->getMonthInfo($params, $request);

            return $this->generateResponse($response, $jsonResponse, 200);
        } catch (Exception $exception) {
            return $this->generateErrorResponse($response, $exception, 400);
        }
    }
}
