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

namespace Google\Service\CloudDataplex;

class GoogleCloudDataplexV1DataScanEventDataQualityAppliedConfigs extends \Google\Model
{
  /**
   * @var bool
   */
  public $rowFilterApplied;
  /**
   * @var float
   */
  public $samplingPercent;

  /**
   * @param bool
   */
  public function setRowFilterApplied($rowFilterApplied)
  {
    $this->rowFilterApplied = $rowFilterApplied;
  }
  /**
   * @return bool
   */
  public function getRowFilterApplied()
  {
    return $this->rowFilterApplied;
  }
  /**
   * @param float
   */
  public function setSamplingPercent($samplingPercent)
  {
    $this->samplingPercent = $samplingPercent;
  }
  /**
   * @return float
   */
  public function getSamplingPercent()
  {
    return $this->samplingPercent;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDataplexV1DataScanEventDataQualityAppliedConfigs::class, 'Google_Service_CloudDataplex_GoogleCloudDataplexV1DataScanEventDataQualityAppliedConfigs');
