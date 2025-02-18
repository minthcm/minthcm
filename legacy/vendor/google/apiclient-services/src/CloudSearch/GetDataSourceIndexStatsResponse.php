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

namespace Google\Service\CloudSearch;

class GetDataSourceIndexStatsResponse extends \Google\Collection
{
  protected $collection_key = 'stats';
  /**
   * @var string
   */
  public $averageIndexedItemCount;
  protected $statsType = DataSourceIndexStats::class;
  protected $statsDataType = 'array';

  /**
   * @param string
   */
  public function setAverageIndexedItemCount($averageIndexedItemCount)
  {
    $this->averageIndexedItemCount = $averageIndexedItemCount;
  }
  /**
   * @return string
   */
  public function getAverageIndexedItemCount()
  {
    return $this->averageIndexedItemCount;
  }
  /**
   * @param DataSourceIndexStats[]
   */
  public function setStats($stats)
  {
    $this->stats = $stats;
  }
  /**
   * @return DataSourceIndexStats[]
   */
  public function getStats()
  {
    return $this->stats;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GetDataSourceIndexStatsResponse::class, 'Google_Service_CloudSearch_GetDataSourceIndexStatsResponse');
