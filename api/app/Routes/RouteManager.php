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

namespace MintHCM\Api\Routes;

use MintHCM\Utils\CustomLoader;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class RouteManager
{
    protected static $_instance;

    protected $app;

    protected $routes = array();
    protected $modules_routes = array();

    protected $routes_locations = [
        'app/Routes/routes/',
        'custom/app/Routes/routes/',
    ];

    protected $modules_locations = [
        'app/Routes/modules/',
        'custom/app/Routes/modules/',
        'modules/{module_name}/api/routes/',
        'custom/{module_name}/api/routes/',
    ];

    public function __construct()
    {
        global $app;

        $this->app = $app;
        $this->setRoutes();
    }

    public static function getInstance()
    {
        if (!is_object(self::$_instance)) {
            self::$_instance = CustomLoader::getObject(RouteManager::class);
        }
        return self::$_instance;
    }

    public function execute()
    {
        $this->buildRoutes($this->routes);
        foreach ($this->modules_routes as $module => $routes) {
            $this->buildModulesRoutes($module, $routes);
        }
    }

    public function getRoutes()
    {
        return array_merge($this->routes, $this->modules_routes);
    }

    protected function buildModulesRoutes($module, &$routes)
    {
        foreach ($routes as $route_name => $route_data) {
            if (!isset($route_data['path'])) {
                continue;
            }
            $route_data['path'] = '/' . $module . $route_data['path'];
            $route_name = $module . '___' . $route_name;
            $this->buildRoute($route_data, $route_name);
        }
    }

    protected function buildRoutes(&$routes)
    {
        foreach ($routes as $route_name => $route_data) {
            $this->buildRoute($route_data, $route_name);
        }
    }

    protected function buildRoute(&$route_data, $route_name)
    {
        $route = $this->addRoute($route_data);
        if (!$route) {
            return null;
        }

        $route->setName($route_name);
    }

    protected function addRoute($route)
    {
        if ($this->shouldSkipRoute($route)) {
            return false;
        }

        $method = $this->getMethod($route);
        if (!$method) {
            return false;
        }

        $route_function = $this->getRouteFunction($route);
        return $this->app->map($method, $route['path'], $route_function);
    }

    protected function getRouteFunction(array $route)
    {
        if (empty($route['class'])) {
            return $route['function'];
        }

        $routeManager = $this;
        return function (...$arguments) use ($route, $routeManager) {
            $constructorArguments = $routeManager->prepareConstructorArguments($this, $route['class']);
            $obj = new $route['class'](...$constructorArguments);
            $callable = empty($route['function']) ? $obj : [$obj, $route['function']];
            return $callable(...$arguments);
        };
    }

    protected function prepareConstructorArguments(ContainerInterface $di, string $class): array
    {
        $reflectionClass = new \ReflectionClass($class);
        $constructor = $reflectionClass->getConstructor();
        if (empty($constructor)) {
            return [];
        }

        $parameters = $constructor->getParameters();
        return array_map(function ($parameter) use ($di) {
            $dependencyType = $parameter->getType()->getName();
            return $di->get($dependencyType);
        }, $parameters);
    }

    protected function shouldSkipRoute($route)
    {
        return empty($route['method'])
        || empty($route['path'])
        || empty($route['class'])
        || !class_exists($route['class'])
            || (
            !method_exists($route['class'], '__invoke')
            && (
                empty($route['function'])
                || !method_exists($route['class'], $route['function'])
            )
        )
        ;
    }

    protected function getMethod($route)
    {
        if (empty($route['method'])) {
            return false;
        }

        if (is_string($route['method'])) {
            return array(strtoupper($route['method']));
        }
        if (is_array($route['method'])) {
            return array_map('strtoupper', $route['method']);
        }
        return false;
    }

    protected function setRoutes()
    {
        global $beanList;
        $this->routes = $this->getRoutesData($this->routes_locations);
        foreach (array_keys($beanList) as $module) {
            $module_locations = $this->getModuleLocations($module);
            $this->modules_routes[$module] = $this->getRoutesData($module_locations);
        }
    }

    protected function getModuleLocations($module_name)
    {
        $replace = function ($location) use ($module_name) {
            return str_replace('{module_name}', $module_name, $location);
        };
        return array_map($replace, $this->modules_locations);
    }

    protected function getRoutesData($locations)
    {
        $response = array();
        foreach ($locations as $location) {
            $files = is_dir($location) ? scandir($location) : false;
            if (empty($files)) {
                continue;
            }

            $files = array_diff($files, array('.', '..'));

            foreach ($files as $file) {
                if (!str_contains($file, ".php")) {
                    continue;
                }

                include $location . "/" . $file;
                if (empty($routes)) {
                    continue;
                }
                $response = array_merge($response, $routes);
            }
        }
        return $response;
    }

}

