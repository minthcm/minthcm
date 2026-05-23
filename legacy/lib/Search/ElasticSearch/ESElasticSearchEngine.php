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
 * Copyright (C) 2018-2025 MintHCM
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
// namespace SuiteCRM\Search\ElasticSearch;
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

use SuiteCRM\Search\ElasticSearch\ElasticSearchEngine;
use SuiteCRM\Search\SearchEngine;
use SuiteCRM\Search\SearchQuery;
use SuiteCRM\Search\SearchResults;
use SuiteCRM\Search\SearchWrapper;

require_once 'lib/Search/ElasticSearch/ElasticSearchEngine.php';
require_once 'include/ESListView/Search/ESSearchResults.php';

/**
 * SearchEngine that use Elasticsearch index for performing almost real-time search.
 */
class ESElasticSearchEngine extends ElasticSearchEngine
{

    /**
     * @inheritdoc
     */
    public function search(SearchQuery $query): SearchResults
    {
        $this->validateQuery($query);
        $params = $this->createSearchParams($query);
        $params = $this->addKeywordToSort($params);
        $start = microtime(true);
        $hits = $this->runElasticSearch($params);
        $results = $this->parseHits($hits);
        $end = microtime(true);
        $searchTime = ($end - $start);

        return new ESSearchResults($results, true, $searchTime, $hits['hits']['total']['value']);
    }

    public function globalSearch(SearchQuery $query): SearchResults {
        global $current_user;
        require_once('../api/vendor/autoload.php');
        $search_manager = MintHCM\Lib\Search\Search::getManager();
            
        $start = microtime(true);
        $search_manager->setElasticACL(!is_admin($current_user));

        $search_manager->setQuery(array(
            "search" => 'global',
            "fields" => array("name.*^5", "*"),
            "items" => 5,
            "query" => $query->getSearchString() . '*',
            "sort_order" => "desc",
        ));
        $hits = $search_manager->search(false, true);
        $results = $this->parseHits($hits);
        $end = microtime(true);
        $searchTime = ($end - $start);
        return new SearchResults($results, true, $searchTime, $hits['hits']['total']['value']);
    }

    protected function addPagination($params, $from, $size) {
        if (isset($from) && isset($size)) {
            $from = (($from - 1) < 0) ? 1 : $from;
            $params['body']['from'] = ($from - 1);
            $params['body']['size'] = $size;
        }

        return $params;
    }

    protected function addKeywordToSort($params)
    {
        $path = 'lib/Search/ElasticSearch/defaultParams.yml';
        if (!file_exists($path)) {
            return $params;
        }
        $parse = new Symfony\Component\Yaml\Parser();
        $parsed = $parse->parseFile('lib/Search/ElasticSearch/defaultParams.yml');
        $module = !empty($params['type']) ? $params['type'] : '';
        $sort_field = !empty($params['body']['sort']) && count($params['body']['sort']) == 1 ? array_key_first($params['body']['sort']) : '';
        $sort_field_exp = explode('.', $sort_field);
        if (!isset($parsed['mappings'][$module])) {
            return $params;
        }
        $mappings = $parsed['mappings'][$module];
        foreach ($sort_field_exp as $sort_field_part) {
            if (!isset($mappings['properties'][$sort_field_part])) {
                return $params;
            }
            $mappings = $mappings['properties'][$sort_field_part];
        }
        if (
            isset($mappings['fields']['keyword']['type'])
            && 'keyword' == $mappings['fields']['keyword']['type']) {
            $params['body']['sort'] = [$sort_field . '.keyword' => $params['body']['sort'][$sort_field]];
        }
        return $params;
    }

    /**
     * Generates the parameter array for the Elasticsearch API from a SearchQuery.
     *
     * @param SearchQuery $query
     *
     * @return array
     */
    protected function createSearchParams(SearchQuery $query): array//MintHCM
    {
        $options = $query->getOptions();
        if ($options['filter_by_module']) {
            $params = [
                'index' => static::getIndexPrefix() . '_' . strtolower($options['module']),
                'body' => [
                    'query' => [
                        'bool' => [
                            'filter' => [
                                //
                            ],
                            'must_not' => [
                                //
                            ],
                        ],
                    ],
                ],
            ];
            $params = $this->addBasicSearch($params, $options['searchPhrase']);
            $params = $this->addFieldsBoosting($params, $options['module']);
            $params = $this->addPagination($params, $query->getFrom(), $query->getSize());
            $params = $this->addSorting($params, $query->getOptions()['sorting']);
            $params = $this->addFilters($params, $query->getOptions()['filters']);
            $params = $this->fixQueries($params);
        } else {
            $searchStr = $query->getSearchString();
            $searchModules = SearchWrapper::getModules();
            $searchModules = array_map('strtolower', $searchModules);
            $searchModules = substr_replace($searchModules, static::getIndexPrefix() . '_', 0, 0);

            $indexes = implode(',', $searchModules);

            // Wildcard character required for Elasticsearch
            $wildcardBe = "*";

            // Override frontend wildcard character
            if (isset($GLOBALS['sugar_config']['search_wildcard_char'])) {
                $wildcardFe = $GLOBALS['sugar_config']['search_wildcard_char'];
                if ($wildcardFe !== $wildcardBe && strlen($wildcardFe) === 1) {
                    $searchStr = str_replace($wildcardFe, $wildcardBe, $searchStr);
                }
            }

            // Add wildcard at the beginning of the search string
            if (isset($GLOBALS['sugar_config']['search_wildcard_infront']) && true === $GLOBALS['sugar_config']['search_wildcard_infront'] && $searchStr[0] !== $wildcardBe) {
                $searchStr = $wildcardBe . $searchStr;
            }

            // Add wildcard at the end of search string
            if ((substr_compare($searchStr, $wildcardBe, -strlen($wildcardBe))) !== 0) {
                $searchStr .= $wildcardBe;
            }

            return [
                'index' => $indexes,
                'body' => [
                    'stored_fields' => [],
                    'from' => $query->getFrom(),
                    'size' => $query->getSize(),
                    'query' => [
                        'simple_query_string' => [
                            'query' => $searchStr,
                            'fields' => ['name.*^5', '*'],
                            'analyzer' => 'standard',
                            'default_operator' => 'OR',
                            'minimum_should_match' => '66%',
                        ],
                    ],
                ],
            ];
        }

        return $params;
    }

    protected function fixQueries($params)
    {
        if ('' == $params['body']['query']['bool']['must']['simple_query_string']['query']) {
            $params['body']['query']['bool']['must']['simple_query_string']['query'] = '*';
        }

        foreach ($params['body']['query']['bool']['filter'] as $filter_paramter) {
            if (is_array($filter_paramter['query_string'])) {
                return $params;
            }
        }

        $params['body']['query']['bool']['filter'][]['query_string']['query'] = '*';

        return $params;
    }

    /**
     * Adds boosting to fields in the search query based on the 'search_boost' value in module vardefs
     *
     * @param array $params
     * @param string $module
     */
    protected function addFieldsBoosting($params, $module)
    {
        $module_bean = BeanFactory::getBean($module);
        $boost_array = $params['body']['query']['bool']['must']['simple_query_string']['fields'] ?? [];
        $mappings = $this->getDefaultMapParams($module);
        foreach ($module_bean->field_defs as $field => $value) {
            if (isset($value['search_boost'])) {
                $field_name = getSimilarIndiceKey($field, $mappings['mappings']['properties']);
                if (is_array($mappings['mappings']['properties'][$field_name]['properties'])) {
                    $boost_array[] = $field_name . '.*^' . $value['search_boost'];
                } else {
                    $boost_array[] = $field_name . '^' . $value['search_boost'];
                }
            }
        }

        if (!in_array('*', $boost_array)) {
            $boost_array[] = '*';
        }

        $params['body']['query']['bool']['must']['simple_query_string']['fields'] = $boost_array;
        return $params;
    }

    public static function getIndexPrefix():string
    {
        return $GLOBALS['sugar_config']['elasticsearch_index_prefix'] ?? $GLOBALS['sugar_config']['unique_key'];
    }
}
