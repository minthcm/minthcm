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

class GoogleCloudDiscoveryengineV1betaSearchInfo extends \Google\Model
{
  /**
   * @var int
   */
  public $offset;
  /**
   * @var string
   */
  public $orderBy;
  /**
   * @var string
   */
  public $searchQuery;

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
   * @param string
   */
  public function setSearchQuery($searchQuery)
  {
    $this->searchQuery = $searchQuery;
  }
  /**
   * @return string
   */
  public function getSearchQuery()
  {
    return $this->searchQuery;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDiscoveryengineV1betaSearchInfo::class, 'Google_Service_DiscoveryEngine_GoogleCloudDiscoveryengineV1betaSearchInfo');
