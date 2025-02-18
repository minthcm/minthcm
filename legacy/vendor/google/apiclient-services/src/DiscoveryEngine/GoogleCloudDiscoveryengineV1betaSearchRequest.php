<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\DiscoveryEngine;

class GoogleCloudDiscoveryengineV1betaSearchRequest extends \Google\Collection
{
  protected $collection_key = 'facetSpecs';
  protected $boostSpecType = GoogleCloudDiscoveryengineV1betaSearchRequestBoostSpec::class;
  protected $boostSpecDataType = '';
  /**
   * @var string
   */
  public $branch;
  /**
   * @var string
   */
  public $canonicalFilter;
  protected $contentSearchSpecType = GoogleCloudDiscoveryengineV1betaSearchRequestContentSearchSpec::class;
  protected $contentSearchSpecDataType = '';
  protected $dataStoreSpecsType = GoogleCloudDiscoveryengineV1betaSearchRequestDataStoreSpec::class;
  protected $dataStoreSpecsDataType = 'array';
  protected $embeddingSpecType = GoogleCloudDiscoveryengineV1betaSearchRequestEmbeddingSpec::class;
  protected $embeddingSpecDataType = '';
  protected $facetSpecsType = GoogleCloudDiscoveryengineV1betaSearchRequestFacetSpec::class;
  protected $facetSpecsDataType = 'array';
  /**
   * @var string
   */
  public $filter;
  protected $imageQueryType = GoogleCloudDiscoveryengineV1betaSearchRequestImageQuery::class;
  protected $imageQueryDataType = '';
  /**
   * @var int
   */
  public $offset;
  /**
   * @var string
   */
  public $orderBy;
  /**
   * @var int
   */
  public $pageSize;
  /**
   * @var string
   */
  public $pageToken;
  /**
   * @var array[]
   */
  public $params;
  /**
   * @var string
   */
  public $query;
  protected $queryExpansionSpecType = GoogleCloudDiscoveryengineV1betaSearchRequestQueryExpansionSpec::class;
  protected $queryExpansionSpecDataType = '';
  /**
   * @var string
   */
  public $rankingExpression;
  /**
   * @var bool
   */
  public $safeSearch;
  protected $spellCorrectionSpecType = GoogleCloudDiscoveryengineV1betaSearchRequestSpellCorrectionSpec::class;
  protected $spellCorrectionSpecDataType = '';
  protected $userInfoType = GoogleCloudDiscoveryengineV1betaUserInfo::class;
  protected $userInfoDataType = '';
  /**
   * @var string[]
   */
  public $userLabels;
  /**
   * @var string
   */
  public $userPseudoId;

  /**
   * @param GoogleCloudDiscoveryengineV1betaSearchRequestBoostSpec
   */
  public function setBoostSpec(GoogleCloudDiscoveryengineV1betaSearchRequestBoostSpec $boostSpec)
  {
    $this->boostSpec = $boostSpec;
  }
  /**
   * @return GoogleCloudDiscoveryengineV1betaSearchRequestBoostSpec
   */
  public function getBoostSpec()
  {
    return $this->boostSpec;
  }
  /**
   * @param string
   */
  public function setBranch($branch)
  {
    $this->branch = $branch;
  }
  /**
   * @return string
   */
  public function getBranch()
  {
    return $this->branch;
  }
  /**
   * @param string
   */
  public function setCanonicalFilter($canonicalFilter)
  {
    $this->canonicalFilter = $canonicalFilter;
  }
  /**
   * @return string
   */
  public function getCanonicalFilter()
  {
    return $this->canonicalFilter;
  }
  /**
   * @param GoogleCloudDiscoveryengineV1betaSearchRequestContentSearchSpec
   */
  public function setContentSearchSpec(GoogleCloudDiscoveryengineV1betaSearchRequestContentSearchSpec $contentSearchSpec)
  {
    $this->contentSearchSpec = $contentSearchSpec;
  }
  /**
   * @return GoogleCloudDiscoveryengineV1betaSearchRequestContentSearchSpec
   */
  public function getContentSearchSpec()
  {
    return $this->contentSearchSpec;
  }
  /**
   * @param GoogleCloudDiscoveryengineV1betaSearchRequestDataStoreSpec[]
   */
  public function setDataStoreSpecs($dataStoreSpecs)
  {
    $this->dataStoreSpecs = $dataStoreSpecs;
  }
  /**
   * @return GoogleCloudDiscoveryengineV1betaSearchRequestDataStoreSpec[]
   */
  public function getDataStoreSpecs()
  {
    return $this->dataStoreSpecs;
  }
  /**
   * @param GoogleCloudDiscoveryengineV1betaSearchRequestEmbeddingSpec
   */
  public function setEmbeddingSpec(GoogleCloudDiscoveryengineV1betaSearchRequestEmbeddingSpec $embeddingSpec)
  {
    $this->embeddingSpec = $embeddingSpec;
  }
  /**
   * @return GoogleCloudDiscoveryengineV1betaSearchRequestEmbeddingSpec
   */
  public function getEmbeddingSpec()
  {
    return $this->embeddingSpec;
  }
  /**
   * @param GoogleCloudDiscoveryengineV1betaSearchRequestFacetSpec[]
   */
  public function setFacetSpecs($facetSpecs)
  {
    $this->facetSpecs = $facetSpecs;
  }
  /**
   * @return GoogleCloudDiscoveryengineV1betaSearchRequestFacetSpec[]
   */
  public function getFacetSpecs()
  {
    return $this->facetSpecs;
  }
  /**
   * @param string
   */
  public function setFilter($filter)
  {
    $this->filter = $filter;
  }
  /**
   * @return string
   */
  public function getFilter()
  {
    return $this->filter;
  }
  /**
   * @param GoogleCloudDiscoveryengineV1betaSearchRequestImageQuery
   */
  public function setImageQuery(GoogleCloudDiscoveryengineV1betaSearchRequestImageQuery $imageQuery)
  {
    $this->imageQuery = $imageQuery;
  }
  /**
   * @return GoogleCloudDiscoveryengineV1betaSearchRequestImageQuery
   */
  public function getImageQuery()
  {
    return $this->imageQuery;
  }
  /**
   * @param int
   */
  public function setOffset($offset)
  {
    $this->offset = $offset;
  }
  /**
   * @return int
   */
  public function getOffset()
  {
    return $this->offset;
  }
  /**
   * @param string
   */
  public function setOrderBy($orderBy)
  {
    $this->orderBy = $orderBy;
  }
  /**
   * @return string
   */
  public function getOrderBy()
  {
    return $this->orderBy;
  }
  /**
   * @param int
   */
  public function setPageSize($pageSize)
  {
    $this->pageSize = $pageSize;
  }
  /**
   * @return int
   */
  public function getPageSize()
  {
    return $this->pageSize;
  }
  /**
   * @param string
   */
  public function setPageToken($pageToken)
  {
    $this->pageToken = $pageToken;
  }
  /**
   * @return string
   */
  public function getPageToken()
  {
    return $this->pageToken;
  }
  /**
   * @param array[]
   */
  public function setParams($params)
  {
    $this->params = $params;
  }
  /**
   * @return array[]
   */
  public function getParams()
  {
    return $this->params;
  }
  /**
   * @param string
   */
  public function setQuery($query)
  {
    $this->query = $query;
  }
  /**
   * @return string
   */
  public function getQuery()
  {
    return $this->query;
  }
  /**
   * @param GoogleCloudDiscoveryengineV1betaSearchRequestQueryExpansionSpec
   */
  public function setQueryExpansionSpec(GoogleCloudDiscoveryengineV1betaSearchRequestQueryExpansionSpec $queryExpansionSpec)
  {
    $this->queryExpansionSpec = $queryExpansionSpec;
  }
  /**
   * @return GoogleCloudDiscoveryengineV1betaSearchRequestQueryExpansionSpec
   */
  public function getQueryExpansionSpec()
  {
    return $this->queryExpansionSpec;
  }
  /**
   * @param string
   */
  public function setRankingExpression($rankingExpression)
  {
    $this->rankingExpression = $rankingExpression;
  }
  /**
   * @return string
   */
  public function getRankingExpression()
  {
    return $this->rankingExpression;
  }
  /**
   * @param bool
   */
  public function setSafeSearch($safeSearch)
  {
    $this->safeSearch = $safeSearch;
  }
  /**
   * @return bool
   */
  public function getSafeSearch()
  {
    return $this->safeSearch;
  }
  /**
   * @param GoogleCloudDiscoveryengineV1betaSearchRequestSpellCorrectionSpec
   */
  public function setSpellCorrectionSpec(GoogleCloudDiscoveryengineV1betaSearchRequestSpellCorrectionSpec $spellCorrectionSpec)
  {
    $this->spellCorrectionSpec = $spellCorrectionSpec;
  }
  /**
   * @return GoogleCloudDiscoveryengineV1betaSearchRequestSpellCorrectionSpec
   */
  public function getSpellCorrectionSpec()
  {
    return $this->spellCorrectionSpec;
  }
  /**
   * @param GoogleCloudDiscoveryengineV1betaUserInfo
   */
  public function setUserInfo(GoogleCloudDiscoveryengineV1betaUserInfo $userInfo)
  {
    $this->userInfo = $userInfo;
  }
  /**
   * @return GoogleCloudDiscoveryengineV1betaUserInfo
   */
  public function getUserInfo()
  {
    return $this->userInfo;
  }
  /**
   * @param string[]
   */
  public function setUserLabels($userLabels)
  {
    $this->userLabels = $userLabels;
  }
  /**
   * @return string[]
   */
  public function getUserLabels()
  {
    return $this->userLabels;
  }
  /**
   * @param string
   */
  public function setUserPseudoId($userPseudoId)
  {
    $this->userPseudoId = $userPseudoId;
  }
  /**
   * @return string
   */
  public function getUserPseudoId()
  {
    return $this->userPseudoId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDiscoveryengineV1betaSearchRequest::class, 'Google_Service_DiscoveryEngine_GoogleCloudDiscoveryengineV1betaSearchRequest');
