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

namespace Google\Service\MyBusinessBusinessInformation;

class TimePeriod extends \Google\Model
{
  /**
   * @var string
   */
  public $closeDay;
  protected $closeTimeType = TimeOfDay::class;
  protected $closeTimeDataType = '';
  /**
   * @var string
   */
  public $openDay;
  protected $openTimeType = TimeOfDay::class;
  protected $openTimeDataType = '';

  /**
   * @param string
   */
  public function setCloseDay($closeDay)
  {
    $this->closeDay = $closeDay;
  }
  /**
   * @return string
   */
  public function getCloseDay()
  {
    return $this->closeDay;
  }
  /**
   * @param TimeOfDay
   */
  public function setCloseTime(TimeOfDay $closeTime)
  {
    $this->closeTime = $closeTime;
  }
  /**
   * @return TimeOfDay
   */
  public function getCloseTime()
  {
    return $this->closeTime;
  }
  /**
   * @param string
   */
  public function setOpenDay($openDay)
  {
    $this->openDay = $openDay;
  }
  /**
   * @return string
   */
  public function getOpenDay()
  {
    return $this->openDay;
  }
  /**
   * @param TimeOfDay
   */
  public function setOpenTime(TimeOfDay $openTime)
  {
    $this->openTime = $openTime;
  }
  /**
   * @return TimeOfDay
   */
  public function getOpenTime()
  {
    return $this->openTime;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(TimePeriod::class, 'Google_Service_MyBusinessBusinessInformation_TimePeriod');
