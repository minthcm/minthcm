<?php

namespace MintHCM\Modules\Meetings\api\controllers;

use MintHCM\Data\BeanFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Response;

class CloseMeeting
{
    public function __invoke(Request $request, Response $response): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $id = $request->getAttribute('id');

        if (empty($id)) {
            throw new HttpBadRequestException($request, 'Parameter "id" is missing or empty.');
        }

        $meeting = BeanFactory::getBean('Meetings', $id);
        if (empty($meeting->id) || $meeting->id !== $id) {
            throw new HttpBadRequestException($request, 'Meeting could not be found.');
        }

        if (!$meeting->ACLAccess('edit')) {
            throw new HttpForbiddenException($request, 'Not authorized to close this meeting.');
        }

        $meeting->status = 'Held';
        if (empty($meeting->save())) {
            throw new HttpInternalServerErrorException($request, 'Meeting could not be saved.');
        }

        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'LBL_CLOSE_MEETING_SUCCESS',
            'action' => 'reload',
        ]));
        return $response;
    }
}
