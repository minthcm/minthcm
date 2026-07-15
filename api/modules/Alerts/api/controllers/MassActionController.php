<?php

namespace MintHCM\Modules\Alerts\api\controllers;

use MintHCM\Modules\Alerts\api\helpers\DataHelper;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class MassActionController
{
    public function readAlerts(Request $request, Response $response, array $args): Response
    {
        $records = $request->getAttribute('records');

        chdir('../legacy/');
        $result = true;
        foreach ($records as $id) {
            $alert = \BeanFactory::getBean('Alerts', $id);
            if (!empty($alert->id) && false == $alert->is_read) {
                if (!DataHelper::isAssignedUserCurrentUser($alert)) {
                    chdir('../api/');
                    $response = $response->withStatus(403);
                    $response->getBody()->write(json_encode(["message" => "No access to record: " . $id]));
                    return $response;
                }
                $alert->is_read = true;
                $result = $result && $alert->save(false);
            }
        }
        $response = $response->withStatus($result ? 200 : 400);
        $response->getBody()->write(json_encode((new ListAction)->getListData()));
        chdir('../api/');

        return $response;
    }

    public function closeAlerts(Request $request, Response $response, array $args): Response
    {
        $records = $request->getAttribute('records');

        chdir('../legacy/');
        $result = true;
        foreach ($records as $id) {
            $alert = \BeanFactory::getBean('Alerts', $id);
            if (!empty($alert->id) && false == $alert->is_closed) {
                if (!DataHelper::isAssignedUserCurrentUser($alert)) {
                    chdir('../api/');
                    $response = $response->withStatus(403);
                    $response->getBody()->write(json_encode(["message" => "No access to record: " . $id]));
                    return $response;
                }
                $alert->is_closed = true;
                $alert->is_read = true;
                $result = $result && $alert->save(false);
            }
        }
        $response = $response->withStatus($result ? 200 : 400);
        $response->getBody()->write(json_encode((new ListAction)->getListData()));
        chdir('../api/');

        return $response;
    }
}
