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

namespace MintHCM\Api\Controllers;

use MintHCM\Data\BeanFactory;
use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Api\Repositories\SchedulerRepository;
use MintHCM\Lib\Search\ElasticSearch\NestedQueryFactory;
use MintHCM\Lib\Search\Search;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class SchedulerController
{
    protected $entityManager;
    protected $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = new SchedulerRepository($this->entityManager->getConnection());
    }

    public function getData(Request $request, Response $response, array $args): Response
    {
        $date_from = $request->getAttribute('date_from');
        $date_to = $request->getAttribute('date_to');
        $parent_type = $request->getAttribute('parent_type');
        $parent_id = $request->getAttribute('parent_id');
        $search = $request->getAttribute('search');
        $participants = $request->getAttribute('participants');

        if (
            empty($date_from)
            || empty($date_to)
            || $date_from >= $date_to
        ) {
            return $response->withStatus(400);
        }

        $parent = null;
        if (!empty($parent_type) && !empty($parent_id)) {
            $parent = BeanFactory::getBean($parent_type, $parent_id);
            if (empty($parent->id)) {
                return $response->withStatus(404);
            } else if (!$parent->ACLAccess('view')) {
                return $response->withStatus(403);
            }
        }
        if (!empty($search)) {
            $participants = array_merge($participants ?? [], $this->searchParticipants($search));
        }

        $data = $this->repository->getData($date_from, $date_to, $parent, $participants);

        $response = $response->withHeader('Content-type', 'application/json');
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    private function searchParticipants($searchQuery)
    {
        global $current_user;
        $search_manager = Search::getManager();
        $search_manager->setElasticACL(!is_admin($current_user));
    
        $searchQuery = trim($searchQuery);
        if (!str_ends_with($searchQuery, '*')) {
            $searchQuery .= '*';
        }

        $participantModules = $this->repository->getParticipantModules();

        $search_manager->setQuery(array(
            "search" => 'global',
            "type" => $participantModules,
            "fields" => ["*__last^5", "*__first^4", "*__name.*^3", "*"],
            "items" => 25,
            "query" => $searchQuery,
            "nestedQuery" => $this->processNestedSecurityGroupsQueries($participantModules, $searchQuery),
            "sort_order" => "desc",
        ));
        $search_result = $search_manager->search(false);
        return $search_result->getHits();
    }

    private function processNestedSecurityGroupsQueries(array $participantModules, $searchQuery): array
    {
        $processed_queries = [];
        $nested_queries = [];
        foreach($participantModules as $module){
            $module_nested_query_object = NestedQueryFactory::getModuleNestedQueryObject($module);
            foreach($module_nested_query_object->getQueries() as $query){
                if(in_array($query, $processed_queries)){
                    continue;
                }
                if(method_exists($module_nested_query_object, $query)){
                    $module_nested_query = $module_nested_query_object->getProcessedQuery($query, ['search' => $searchQuery]);
                    if(!empty($module_nested_query)){
                        $nested_queries[] = $module_nested_query;
                        $processed_queries[] = $query;
                    }
                }
            }
        }
        return $nested_queries;
    }

}
