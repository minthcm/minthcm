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

namespace MintHCM\Api\Controllers\Module;

use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Elasticsearch\Common\Exceptions\InvalidArgumentException;
use MintHCM\Lib\Search\Search;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

class ListController
{
    private $request, $params, $search_result, $list_response;

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $response = $response->withHeader('Content-type', 'application/json');

        $this->setParams($this->request);
        $this->runElasticSearch();
        $data = $this->getData();

        $response->getBody()->write(json_encode($data));
        return $response;
    }

    private function getData()
    {
        return array(
            'total' => $this->search_result->getTotal(),
            'next_page_exists' => $this->search_result->getNextPageExists(),
            'offset' => $this->search_result->getNextOffset(),
            'results' => $this->search_result->getBeansAsJsonArray(),
        );
    }

    private function setParams(Request $request)
    {
        global $mint_config;

        $size = $request->getAttribute('items') ?? ($mint_config['search']['default_page_size'] ?? 25);
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        $params = array();
        $params['search'] = 'list';
        $params['items'] = $size;
        $params['from'] = $request->getAttribute('offset') ?? -1;
        $params['sort_by'] = $request->getAttribute('sortBy') ?? null;
        $params['sort_order'] = $request->getAttribute('sortOrder') ?? 'asc';
        $params['filters'] = $request->getAttribute('filters') ?? array();
        $params['type'] = str_replace('/', '', $route->getPattern());
        $this->params = $params;
    }

    private function runElasticSearch()
    {
        try {
            $search_manager = Search::getManager();
            $search_manager->setQuery($this->params);
            $this->search_result = $search_manager->search(true);

        } catch (BadRequest400Exception) {
            throw new HttpBadRequestException($this->request);
        } catch (InvalidArgumentException) {
            throw new HttpInternalServerErrorException($this->request);
        }
    }

}
