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
 * Copyright (C) 2018-2024 MintHCM
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

namespace MintHCM\Api\Middlewares\Routes;

use MintHCM\Api\Middlewares\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpNotFoundException;

class RouteAccessMiddleware extends Middleware
{
    const ALL_ACCESS = 'all';
    const FRONTEND_ACCESS = 'frontend';
    const MOBILE_ACCESS = 'mobile';
    CONST EXTERNAL_ACCESS = 'external';

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        if (!$this->hasAccessToRoute($request)) {
            throw new HttpNotFoundException($request);
        }

        return $handler->handle($request);
    }

    private function hasAccessToRoute(Request $request): bool
    {
        $route_data = $this->getRouteData($request);
        $route_access = !empty($route_data['options']['access']) ? $route_data['options']['access'] : self::ALL_ACCESS;
        if ($route_access === self::ALL_ACCESS) {
            return true;
        }

        $client_access = $this->getCLientAccess();
        
        if (is_string($route_access)) {
            return $route_access === $client_access;
        }

        if (is_array($route_access)) {
            return in_array($client_access, $route_access);
        } 

        return false;
    }

    private function getCLientAccess(): string
    {
        global $api_client;
        switch ($api_client) {
            case self::FRONTEND_ACCESS:
                return self::FRONTEND_ACCESS;
            case self::MOBILE_ACCESS:
                return self::MOBILE_ACCESS;
            default:
                return self::EXTERNAL_ACCESS;
        }
    }
}
