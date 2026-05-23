<?php
namespace MintHCM\Api\Controllers\Module;

use MintHCM\Data\MassActions\MassActionLoader;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpForbiddenException;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

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

        $mass_action = MassActionLoader::getAction($action, $module, $ids);

        if (!$mass_action->hasAccess()) {
            throw new HttpForbiddenException($request);
        }
        
        if (method_exists($mass_action, 'setArrayFields')) {
            $update_fields = $request->getAttribute('update_fields');
            if (empty($update_fields) || !is_array($update_fields)) {
                throw new HttpBadRequestException($request, "update_fields is required and should be an array");
            }
            $mass_action->setArrayFields($update_fields);
        }

        $result = $mass_action->execute();

        $response->getBody()->write(json_encode($result));
        return $response;
    }
}
