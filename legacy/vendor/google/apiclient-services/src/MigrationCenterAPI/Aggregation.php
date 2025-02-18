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

namespace Google\Service\MigrationCenterAPI;

class Aggregation extends \Google\Model
{
  protected $countType = AggregationCount::class;
  protected $countDataType = '';
  /**
   * @var string
   */
  public $field;
  protected $frequencyType = AggregationFrequency::class;
  protected $frequencyDataType = '';
  protected $histogramType = AggregationHistogram::class;
  protected $histogramDataType = '';
  protected $sumType = AggregationSum::class;
  protected $sumDataType = '';

  /**
   * @param AggregationCount
   */
  public function setCount(AggregationCount $count)
  {
    $this->count = $count;
  }
  /**
   * @return AggregationCount
   */
  public function getCount()
  {
    return $this->count;
  }
  /**
   * @param string
   */
  public function setField($field)
  {
    $this->field = $field;
  }
  /**
   * @return string
   */
  public function getField()
  {
    return $this->field;
  }
  /**
   * @param AggregationFrequency
   */
  public function setFrequency(AggregationFrequency $frequency)
  {
    $this->frequency = $frequency;
  }
  /**
   * @return AggregationFrequency
   */
  public function getFrequency()
  {
    return $this->frequency;
  }
  /**
   * @param AggregationHistogram
   */
  public function setHistogram(AggregationHistogram $histogram)
  {
    $this->histogram = $histogram;
  }
  /**
   * @return AggregationHistogram
   */
  public function getHistogram()
  {
    return $this->histogram;
  }
  /**
   * @param AggregationSum
   */
  public function setSum(AggregationSum $sum)
  {
    $this->sum = $sum;
  }
  /**
   * @return AggregationSum
   */
  public function getSum()
  {
    return $this->sum;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Aggregation::class, 'Google_Service_MigrationCenterAPI_Aggregation');
