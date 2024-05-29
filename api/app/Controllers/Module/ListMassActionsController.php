<?php

namespace MintHCM\Api\Controllers\Module;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Slim\Exception\HttpBadRequestException;

class ListMassActionsController
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $action = $request->getAttribute('action');
        $ids = $request->getAttribute('ids');

        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $module = explode('/', $route->getPattern())[1] ?? '';

        if (empty($module) || empty($action) || empty($ids)) {
            throw new HttpBadRequestException($request);
        }

        $mass_action_class = 'MintHCM\\Data\\MassActions\\Actions\\' . $action;
        if (!class_exists($mass_action_class)) {
            throw new HttpNotFoundException($request);
        }

        $mass_action = new $mass_action_class($module, $ids);

        if (!$mass_action->hasAccess()) {
            throw new HttpForbiddenException($request);
        }

        $result = $mass_action->execute();

        $response->getBody()->write(json_encode($result));
        return $response;
    }
}
