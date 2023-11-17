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

namespace SuiteCRM\Search\ElasticSearch;

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

use Elasticsearch\Client;
use SuiteCRM\Exception\InvalidArgumentException;
use SuiteCRM\Search\SearchEngine;
use SuiteCRM\Search\SearchQuery;
use SuiteCRM\Search\SearchResults;
use SuiteCRM\Search\SearchWrapper;

/**
 * SearchEngine that use Elasticsearch index for performing almost real-time search.
 */
class ElasticSearchEngine extends SearchEngine
{

   /** @var Client */
   private $client;
   /** @var string */
   private $index = 'main';

   /**
    * ElasticSearchEngine constructor.
    *
    * @param Client|null $client
    */
   public function __construct(Client $client = null)
   {
      $this->client = $client ?? ElasticSearchClientBuilder::getClient();
   }

   /**
    * @throws InvalidArgumentException
    * @inheritdoc
    */
   public function search(SearchQuery $query): SearchResults
   {
      $this->validateQuery($query);
      $params = $this->createSearchParams($query);
      $start = microtime(true);
      $hits = $this->runElasticSearch($params);
      $results = $this->parseHits($hits);
      $end = microtime(true);
      $searchTime = ($end - $start);
      return new SearchResults($results, true, $searchTime, $hits['hits']['total']);
   }

   /**
    * @return string
    */
   public function getIndex()
   {
      return $this->index;
   }

   /**
    * @param string $index
    */
   public function setIndex($index)
   {
      $this->index = $index;
   }

   /**
    * @param SearchQuery $query
    */
   protected function validateQuery(SearchQuery $query): void
   {
      $query->trim();
      $query->convertEncoding();
   }

   /**
    * Generates the parameter array for the Elasticsearch API from a SearchQuery.
    *
    * @param SearchQuery $query
    *
    * @return array
    */
    protected function createSearchParams(SearchQuery $query): array  //MintHCM
    {
       $options = $query->getOptions();
       if ($options['filter_by_module']) {
         $params = [
            'index' => $GLOBALS['sugar_config']['unique_key'].'_'.strtolower($options['module']),
            'body' => [
               'query' => [
                  'bool' => [
                     'filter' => [
                        //
                     ],
                     'must_not' => [
                        //
                     ]
                  ]
               ]
            ]
         ];
          $params= $this->addBasicSearch($params, $options['searchPhrase']);
          $params = $this->addPagination($params, $query->getFrom(), $query->getSize());
          $params = $this->addSorting($params, $query->getOptions()['sorting']);
          $params = $this->addFilters($params, $query->getOptions()['filters']);
       } else {
            $searchStr = $query->getSearchString();
            $searchModules = SearchWrapper::getModules();
            $searchModules = array_map('strtolower', $searchModules);
            $searchModules = substr_replace($searchModules, $GLOBALS['sugar_config']['unique_key'].'_', 0, 0);

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
            if (isset($GLOBALS['sugar_config']['search_wildcard_infront']) &&
                $GLOBALS['sugar_config']['search_wildcard_infront'] === true && $searchStr[0] !== $wildcardBe) {
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
                        'query_string' => [
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
    protected function addBasicSearch($params, $query_string) //MintHCM
    {
       if(!empty($query_string)){
         $query_string = str_replace('+', '', strtolower($query_string));
         $params['body']['query']['bool']['must'] = [
            'simple_query_string' =>[
            "query" => $query_string.'*',
            "fields" => ["*","*name^5","subject^4"]
            ],  
         ];
      }
       return $params;
    }
 

   protected function addPagination($params, $from, $size) //MintHCM
   {
      if (isset($from) && isset($size)) {
         $from = (($from - 1)<0)? 1 :$from;
         $params['body']['from'] = ($from - 1) * $size;
         $params['body']['size'] = $size;
      }

      return $params;
   }

   private function addSorting($params, $data)
   {
      if (isset($data)) {
         $column = $data['column'];
         $direction = $data['direction'];
         $parsedData = [
            $column ? $column : '_score' => [
               'order' => $direction ? $direction : 'asc'
            ]
      ];
         $params['body']['sort'] = $parsedData;
      }

      return $params;
   }

   private function addFilters($params, $data)
   {
         if (isset($data)) {
         $params['body']['query']['bool']['filter'] = is_array($params['body']['query']['bool']['filter'])? array_merge($params['body']['query']['bool']['filter'],$data['filter']):$data['filter'];
         $params['body']['query']['bool']['must_not'] = is_array($params['body']['query']['bool']['must_not'])? array_merge($params['body']['query']['bool']['must_not'],$data['must_not']):$data['must_not'];
      }

      return $params;
   }

   /**
    * Calls the Elasticsearch API.
    *
    * @param array $params
    *
    * @return array
    */
   protected function runElasticSearch(array $params): array //MintHCM
   {
        return $this->client->search($params);
   }

   /**
    * Reads the array returned from the Elasticsearch API
    * and converts it into an associative array of ids, grouped by Module.
    *
    * @param array $hits
    *
    * @return array
    */
    protected function parseHits(array $hits): array //MintHCM
    {
        $hitsArray = $hits['hits']['hits'];

        $initialResults = [];

        foreach ($hitsArray as $hit) {
            $recordModule = $hit['_index'];
            $initialResults[$recordModule][] = $hit['_id'];
        }

        $searchResults = [];

        foreach ($initialResults as $index => $hit) {
            $params = ['index' => $index];
            $meta = $this->client->indices()->getMapping($params);
            $moduleName = $meta[$index]['mappings']['_meta']['module_name'];
            $searchResults[$moduleName] = $hit;
        }

        return $searchResults;
    }
}
