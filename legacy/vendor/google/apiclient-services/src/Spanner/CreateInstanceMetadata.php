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

namespace Google\Service\Spanner;

class CreateInstanceMetadata extends \Google\Model
{
  /**
   * @var string
   */
  public $cancelTime;
  /**
   * @var string
   */
  public $endTime;
  /**
   * @var string
   */
  public $expectedFulfillmentPeriod;
  protected $instanceType = Instance::class;
  protected $instanceDataType = '';
  /**
   * @var string
   */
  public $startTime;

  /**
   * @param string
   */
  public function setCancelTime($cancelTime)
  {
    $this->cancelTime = $cancelTime;
  }
  /**
   * @return string
   */
  public function getCancelTime()
  {
    return $this->cancelTime;
  }
  /**
   * @param string
   */
  public function setEndTime($endTime)
  {
    $this->endTime = $endTime;
  }
  /**
   * @return string
   */
  public function getEndTime()
  {
    return $this->endTime;
  }
  /**
   * @param string
   */
  public function setExpectedFulfillmentPeriod($expectedFulfillmentPeriod)
  {
    $this->expectedFulfillmentPeriod = $expectedFulfillmentPeriod;
  }
  /**
   * @return string
   */
  public function getExpectedFulfillmentPeriod()
  {
    return $this->expectedFulfillmentPeriod;
  }
  /**
   * @param Instance
   */
  public function setInstance(Instance $instance)
  {
    $this->instance = $instance;
  }
  /**
   * @return Instance
   */
  public function getInstance()
  {
    return $this->instance;
  }
  /**
   * @param string
   */
  public function setStartTime($startTime)
  {
    $this->startTime = $startTime;
  }
  /**
   * @return string
   */
  public function getStartTime()
  {
    return $this->startTime;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CreateInstanceMetadata::class, 'Google_Service_Spanner_CreateInstanceMetadata');
