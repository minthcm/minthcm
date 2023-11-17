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
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use SuiteCRM\Search\ElasticSearch\ElasticSearchClientBuilder;
use SuiteCRM\Search\Exceptions\SearchInvalidRequestException;
use SuiteCRM\Search\SearchEngine;
use SuiteCRM\Search\SearchQuery;
use SuiteCRM\Search\SearchResults;
use SuiteCRM\Search\ElasticSearch\ElasticSearchEngine;

require_once 'lib/Search/ElasticSearch/ElasticSearchEngine.php';
require_once 'include/ESListView/Search/ESSearchResults.php';

/**
 * SearchEngine that use Elasticsearch index for performing almost real-time search.
 */
class ESElasticSearchEngine extends ElasticSearchEngine {

    /**
     * @inheritdoc
     */
    public function search(SearchQuery $query): SearchResults {
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

    protected function addPagination($params, $from, $size) {
        if (isset($from) && isset($size)) {
            $from = (($from - 1)<0)? 1 :$from;
            $params['body']['from'] = ($from - 1) ;
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
        $sort_field_exp = explode('.',$sort_field);
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
            && $mappings['fields']['keyword']['type'] == 'keyword'
        ) {
            $params['body']['sort'] = [$sort_field.'.keyword' => $params['body']['sort'][$sort_field]];
        }
        return $params;
    }

}
