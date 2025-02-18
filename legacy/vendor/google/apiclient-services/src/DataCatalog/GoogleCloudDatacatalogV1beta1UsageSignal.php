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

namespace Google\Service\DataCatalog;

class GoogleCloudDatacatalogV1beta1UsageSignal extends \Google\Model
{
  public $updateTime;
  protected $usageWithinTimeRangeType = GoogleCloudDatacatalogV1beta1UsageStats::class;
  protected $usageWithinTimeRangeDataType = 'map';

  public function setUpdateTime($updateTime)
  {
    $this->updateTime = $updateTime;
  }
  public function getUpdateTime()
  {
    return $this->updateTime;
  }
  /**
   * @param GoogleCloudDatacatalogV1beta1UsageStats[]
   */
  public function setUsageWithinTimeRange($usageWithinTimeRange)
  {
    $this->usageWithinTimeRange = $usageWithinTimeRange;
  }
  /**
   * @return GoogleCloudDatacatalogV1beta1UsageStats[]
   */
  public function getUsageWithinTimeRange()
  {
    return $this->usageWithinTimeRange;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDatacatalogV1beta1UsageSignal::class, 'Google_Service_DataCatalog_GoogleCloudDatacatalogV1beta1UsageSignal');
