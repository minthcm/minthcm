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

namespace Google\Service\CloudMachineLearningEngine;

class GoogleCloudMlV1AutoScaling extends \Google\Collection
{
  protected $collection_key = 'metrics';
  /**
   * @var int
   */
  public $maxNodes;
  protected $metricsType = GoogleCloudMlV1MetricSpec::class;
  protected $metricsDataType = 'array';
  /**
   * @var int
   */
  public $minNodes;

  /**
   * @param int
   */
  public function setMaxNodes($maxNodes)
  {
    $this->maxNodes = $maxNodes;
  }
  /**
   * @return int
   */
  public function getMaxNodes()
  {
    return $this->maxNodes;
  }
  /**
   * @param GoogleCloudMlV1MetricSpec[]
   */
  public function setMetrics($metrics)
  {
    $this->metrics = $metrics;
  }
  /**
   * @return GoogleCloudMlV1MetricSpec[]
   */
  public function getMetrics()
  {
    return $this->metrics;
  }
  /**
   * @param int
   */
  public function setMinNodes($minNodes)
  {
    $this->minNodes = $minNodes;
  }
  /**
   * @return int
   */
  public function getMinNodes()
  {
    return $this->minNodes;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudMlV1AutoScaling::class, 'Google_Service_CloudMachineLearningEngine_GoogleCloudMlV1AutoScaling');
