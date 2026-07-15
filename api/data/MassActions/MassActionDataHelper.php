<?php

namespace MintHCM\Data\MassActions;

use MintHCM\Lib\Search\Search;

class MassActionDataHelper
{
    public static function getRecordIds($module_name, $filter, $returnArray = true)
    {
        global $current_user;
        $search_manager = Search::getManager();
        $search_manager->setElasticACL(!is_admin($current_user));
        $items = 10000;
        $from = 0;
        $records = [];
        while($from < $items){
            $search_manager->setQuery([
                'search' => 'list',
                'items' => 10000,
                'from' => $from,
                'sort_order' => 'desc',
                'type' => $module_name,
                'filters' => static::parseFilters($filter),
                "fields" => array("*__last^5", "*__first^4", "*__name.*^3", "*"),
            ]);
            $result = $search_manager->search(true);
            $records = array_merge($records, $result->getGroupedIds()[$module_name] ?? []);
            $from += 10000;
            $items = $result->getTotal();
        }

        if(!$returnArray){
            return implode(",", $records);
        }
        return $records;
    }

    public static function parseFilters($request_filter)
    {
        if(!is_array($request_filter)){
            $request_filter = json_decode(html_entity_decode($request_filter), true);
        }
        $filters = ['filter' => [], 'must_not' => [], 'must' => []];
        $searchPhrase = $request_filter['searchPhrase'];
        if (strlen($searchPhrase)) {
            $searchPhrase = strtolower(str_replace('+', '', $searchPhrase));
            $filters['must'][] = [
                'query_string' => [
                    'value' => $searchPhrase,
                ],
            ];
        }
        $filters_attribute = $request_filter['filters'] ?? [];
        if (!empty($filters_attribute)) {
            $filters['filter'] = array_merge($filters['filter'], $filters_attribute['filter'] ?? []);
            $filters['must_not'] = array_merge($filters['must_not'], $filters_attribute['must_not'] ?? []);
            $filters['must'] = array_merge($filters['must'], $filters_attribute['must'] ?? []);
        }

        if ($request_filter['myObjects'] === true) {
            global $current_user;
            $filters['filter'][] = ['term' => ['meta.assigned.user_id.keyword' => $current_user->id]];
        }
        return $filters;
    }
}
