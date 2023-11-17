<?php


/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM, 
 * Copyright (C) 2018-2023 MintHCM
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM" 
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo. 
 * If the display of the logos is not reasonably feasible for technical reasons, the 
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and 
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */

namespace MintHCM\Api\Middlewares\Params;

use MintHCM\Api\Middlewares\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpBadRequestException;

class ParamsMiddleware extends Middleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $route = $this->getRoute($request);
        $route_data = $this->getRouteData($request);
        $params = $request->getQueryParams();
        $bodyParams = $request->getParsedBody() ?? [];
        $this->checkPathParams($request, $route_data['pathParams'] ?? []);
        $this->validationParams($request, $params, $route_data['queryParams'] ?? [], 'queryParams');
        $this->validationParams($request, $bodyParams, $route_data['bodyParams'] ?? [], 'bodyParams');

        return $handler->handle($request);
    }

    protected function checkPathParams(Request &$request, array $params_data)
    {
        $route = $this->getRoute($request);
        $path_params = $route->getArguments();
        foreach ($params_data as $name => $data) {
            if (!$data || !is_array($data) || !class_exists($data['type'])) {
                throw new HttpBadRequestException($request);
            }
            $class = new $data['type'];
            $parsed_value = $class($request, $path_params[$name], $data['required'] ?? true);
            $request = $request->withAttribute($name, $parsed_value);
        }
    }

    protected function validationParams(Request &$request, array $params, array $params_data, string $type)
    {
        $globalParams = $this->getGlobalAcceptedParams($type);
        $params = array_diff_key($params, array_fill_keys($globalParams, $globalParams));
        if (empty($params_data) && !empty($params)) {
            throw new HttpBadRequestException($request);
        }

        $empty_params_data = array_fill_keys(array_keys($params_data), null);
        $merged_params = array_merge($empty_params_data, $params);

        foreach ($merged_params as $name => $value) {
            $data = $params_data[$name] ?? false;
            if (!$data || !is_array($data) || !class_exists($data['type'])) {
                throw new HttpBadRequestException($request);
            }
            $class = new $data['type'];
            $parsed_value = $class($request, $value, $data['required'] ?? false);
            $request = $request->withAttribute($name, $parsed_value);
        }

    }

    protected function getGlobalAcceptedParams(string $type): array
    {
        $global_params = include "app/Constansts/global_params.php";

        return $global_params[$type] ?? array();
    }
}
