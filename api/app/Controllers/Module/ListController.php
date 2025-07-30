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

namespace MintHCM\Api\Controllers\Module;

use Doctrine\ORM\EntityManagerInterface;
use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Elasticsearch\Common\Exceptions\InvalidArgumentException;
use MintHCM\Lib\Search\Search;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use UserPreference;

#[\AllowDynamicProperties]
class ListController
{
    private $request, $params, $search_result;
    protected $entityManager;

    const DEFAULT_SORT_BY = '_score';

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        // Workaround for api/lib/Search/Base/SearchResult.php
        // Passing EntityManager by constructor could make a mess with class structure
        // It should be replaced with a normal solution
        global $entityManager;
        $entityManager = $this->entityManager;
    }
    public function getListData(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $response = $response->withHeader('Content-type', 'application/json');

        $this->setParams($this->request);
        $this->runElasticSearch();
        $data = $this->getData();

        $response->getBody()->write(json_encode($data));
        return $response;
    }

    public function savePreferences(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $response = $response->withHeader('Content-type', 'application/json');

        global $current_user;
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        preg_match('/(?<=\/)([^\/]+)/m', $route->getPattern(), $matches);
        $module = $matches[0];
        $preferences = $request->getAttribute('preferences');
        global $beanList;
        if (!isset($beanList[$module]) || empty($beanList[$module])) {
            throw new HttpBadRequestException($request, "Module not found");
        }
        if (!empty($preferences) && is_array($preferences) && !empty($module)) {
            (new UserPreference($current_user))->setPreference($module, $preferences, 'eslist');
        }
        $response->getBody()->write(json_encode(true));
        return $response;
    }

    private function getData()
    {
        return array(
            'total' => $this->search_result->getTotal(),
            'module' => $this->params['type'],
            'offset' => $this->search_result->getCurrentOffset(),
            'results' => $this->search_result->getBeansAsJsonArray(),
        );
    }

    private function setParams(Request $request)
    {
        global $mint_config;
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        $params = array();
        $params['filters'] = ["bool" => []];
        $params['search'] = 'list';
        $params['items'] = $request->getAttribute('items') ?? ($mint_config['search']['default_page_size'] ?? 25);
        $params['from'] = $request->getAttribute('page') && $request->getAttribute('page') > 0 ? ($request->getAttribute('page') - 1) * $params['items'] : -1;
        $params['sort_by'] = $request->getAttribute('sortBy') ?? static::DEFAULT_SORT_BY;
        $params['sort_order'] = $params['sort_by'] == static::DEFAULT_SORT_BY ? 'desc' : $request->getAttribute('sortOrder') ?? 'asc';
        $params['type'] = str_replace('/', '', $route->getPattern());
        $params['filters'] = $this->getParsedFilters($request);
        $params["fields"] = array("*__last^5", "*__first^4", "*__name.*^3", "*");
        $this->params = $params;
    }
    protected function getParsedFilters(Request $request)
    {
        $filters = ['filter' => [], 'must_not' => [], 'must' => []];
        $searchPhrase = $request->getAttribute('searchPhrase');
        if (strlen($searchPhrase)) {
            $searchPhrase = strtolower(str_replace('+', '', $searchPhrase));
            $filters['must'][] = [
                'query_string' => [
                    'value' => $searchPhrase,
                ],
            ];
        }
        $filters_attribute = $request->getAttribute('filters') ?? [];
        if (!empty($filters_attribute)) {
            $filters['filter'] = array_merge($filters['filter'], $filters_attribute['filter'] ?? []);
            $filters['must_not'] = array_merge($filters['must_not'], $filters_attribute['must_not'] ?? []);
            $filters['must'] = array_merge($filters['must'], $filters_attribute['must'] ?? []);
        }

        if ($request->getAttribute('myObjects') === true) {
            global $current_user;
            $filters['filter'][] = ['term' => ['meta.assigned.user_id.keyword' => $current_user->id]];
        }
        return $filters;
    }
    private function runElasticSearch()
    {
        try {
            $search_manager = Search::getManager();
            global $current_user;
            $search_manager->setElasticACL(!is_admin($current_user));
            $search_manager->setQuery($this->params);
            $this->search_result = $search_manager->search(true);
        } catch (BadRequest400Exception $e) {
            throw new HttpBadRequestException($this->request, $e->getMessage());
        } catch (InvalidArgumentException $e) {
            throw new HttpInternalServerErrorException($this->request, $e->getMessage());
        }
    }
}
