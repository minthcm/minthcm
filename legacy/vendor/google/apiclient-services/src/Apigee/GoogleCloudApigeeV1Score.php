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

namespace Google\Service\Apigee;

class GoogleCloudApigeeV1Score extends \Google\Collection
{
  protected $collection_key = 'subcomponents';
  protected $componentType = GoogleCloudApigeeV1ScoreComponent::class;
  protected $componentDataType = '';
  protected $subcomponentsType = GoogleCloudApigeeV1ScoreComponent::class;
  protected $subcomponentsDataType = 'array';
  protected $timeRangeType = GoogleTypeInterval::class;
  protected $timeRangeDataType = '';

  /**
   * @param GoogleCloudApigeeV1ScoreComponent
   */
  public function setComponent(GoogleCloudApigeeV1ScoreComponent $component)
  {
    $this->component = $component;
  }
  /**
   * @return GoogleCloudApigeeV1ScoreComponent
   */
  public function getComponent()
  {
    return $this->component;
  }
  /**
   * @param GoogleCloudApigeeV1ScoreComponent[]
   */
  public function setSubcomponents($subcomponents)
  {
    $this->subcomponents = $subcomponents;
  }
  /**
   * @return GoogleCloudApigeeV1ScoreComponent[]
   */
  public function getSubcomponents()
  {
    return $this->subcomponents;
  }
  /**
   * @param GoogleTypeInterval
   */
  public function setTimeRange(GoogleTypeInterval $timeRange)
  {
    $this->timeRange = $timeRange;
  }
  /**
   * @return GoogleTypeInterval
   */
  public function getTimeRange()
  {
    return $this->timeRange;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudApigeeV1Score::class, 'Google_Service_Apigee_GoogleCloudApigeeV1Score');
