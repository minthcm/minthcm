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

namespace Google\Service\CloudRetail;

class GoogleCloudRetailV2SearchResponseFacetFacetValue extends \Google\Model
{
  /**
   * @var string
   */
  public $count;
  protected $intervalType = GoogleCloudRetailV2Interval::class;
  protected $intervalDataType = '';
  public $maxValue;
  public $minValue;
  /**
   * @var string
   */
  public $value;

  /**
   * @param string
   */
  public function setCount($count)
  {
    $this->count = $count;
  }
  /**
   * @return string
   */
  public function getCount()
  {
    return $this->count;
  }
  /**
   * @param GoogleCloudRetailV2Interval
   */
  public function setInterval(GoogleCloudRetailV2Interval $interval)
  {
    $this->interval = $interval;
  }
  /**
   * @return GoogleCloudRetailV2Interval
   */
  public function getInterval()
  {
    return $this->interval;
  }
  public function setMaxValue($maxValue)
  {
    $this->maxValue = $maxValue;
  }
  public function getMaxValue()
  {
    return $this->maxValue;
  }
  public function setMinValue($minValue)
  {
    $this->minValue = $minValue;
  }
  public function getMinValue()
  {
    return $this->minValue;
  }
  /**
   * @param string
   */
  public function setValue($value)
  {
    $this->value = $value;
  }
  /**
   * @return string
   */
  public function getValue()
  {
    return $this->value;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudRetailV2SearchResponseFacetFacetValue::class, 'Google_Service_CloudRetail_GoogleCloudRetailV2SearchResponseFacetFacetValue');
