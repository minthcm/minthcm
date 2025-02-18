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

namespace Google\Service\Bigquery;

class MaterializedViewDefinition extends \Google\Model
{
  /**
   * @var bool
   */
  public $allowNonIncrementalDefinition;
  /**
   * @var bool
   */
  public $enableRefresh;
  /**
   * @var string
   */
  public $lastRefreshTime;
  /**
   * @var string
   */
  public $maxStaleness;
  /**
   * @var string
   */
  public $query;
  /**
   * @var string
   */
  public $refreshIntervalMs;

  /**
   * @param bool
   */
  public function setAllowNonIncrementalDefinition($allowNonIncrementalDefinition)
  {
    $this->allowNonIncrementalDefinition = $allowNonIncrementalDefinition;
  }
  /**
   * @return bool
   */
  public function getAllowNonIncrementalDefinition()
  {
    return $this->allowNonIncrementalDefinition;
  }
  /**
   * @param bool
   */
  public function setEnableRefresh($enableRefresh)
  {
    $this->enableRefresh = $enableRefresh;
  }
  /**
   * @return bool
   */
  public function getEnableRefresh()
  {
    return $this->enableRefresh;
  }
  /**
   * @param string
   */
  public function setLastRefreshTime($lastRefreshTime)
  {
    $this->lastRefreshTime = $lastRefreshTime;
  }
  /**
   * @return string
   */
  public function getLastRefreshTime()
  {
    return $this->lastRefreshTime;
  }
  /**
   * @param string
   */
  public function setMaxStaleness($maxStaleness)
  {
    $this->maxStaleness = $maxStaleness;
  }
  /**
   * @return string
   */
  public function getMaxStaleness()
  {
    return $this->maxStaleness;
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
   * @param string
   */
  public function setRefreshIntervalMs($refreshIntervalMs)
  {
    $this->refreshIntervalMs = $refreshIntervalMs;
  }
  /**
   * @return string
   */
  public function getRefreshIntervalMs()
  {
    return $this->refreshIntervalMs;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(MaterializedViewDefinition::class, 'Google_Service_Bigquery_MaterializedViewDefinition');
