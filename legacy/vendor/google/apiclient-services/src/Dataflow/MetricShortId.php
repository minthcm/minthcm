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

namespace Google\Service\Dataflow;

class MetricShortId extends \Google\Model
{
  /**
   * @var int
   */
  public $metricIndex;
  /**
   * @var string
   */
  public $shortId;

  /**
   * @param int
   */
  public function setMetricIndex($metricIndex)
  {
    $this->metricIndex = $metricIndex;
  }
  /**
   * @return int
   */
  public function getMetricIndex()
  {
    return $this->metricIndex;
  }
  /**
   * @param string
   */
  public function setShortId($shortId)
  {
    $this->shortId = $shortId;
  }
  /**
   * @return string
   */
  public function getShortId()
  {
    return $this->shortId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(MetricShortId::class, 'Google_Service_Dataflow_MetricShortId');
