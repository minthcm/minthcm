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

namespace Google\Service\Baremetalsolution;

class InstanceQuota extends \Google\Model
{
  /**
   * @var int
   */
  public $availableMachineCount;
  /**
   * @var string
   */
  public $gcpService;
  /**
   * @var string
   */
  public $instanceType;
  /**
   * @var string
   */
  public $location;
  /**
   * @var string
   */
  public $name;

  /**
   * @param int
   */
  public function setAvailableMachineCount($availableMachineCount)
  {
    $this->availableMachineCount = $availableMachineCount;
  }
  /**
   * @return int
   */
  public function getAvailableMachineCount()
  {
    return $this->availableMachineCount;
  }
  /**
   * @param string
   */
  public function setGcpService($gcpService)
  {
    $this->gcpService = $gcpService;
  }
  /**
   * @return string
   */
  public function getGcpService()
  {
    return $this->gcpService;
  }
  /**
   * @param string
   */
  public function setInstanceType($instanceType)
  {
    $this->instanceType = $instanceType;
  }
  /**
   * @return string
   */
  public function getInstanceType()
  {
    return $this->instanceType;
  }
  /**
   * @param string
   */
  public function setLocation($location)
  {
    $this->location = $location;
  }
  /**
   * @return string
   */
  public function getLocation()
  {
    return $this->location;
  }
  /**
   * @param string
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(InstanceQuota::class, 'Google_Service_Baremetalsolution_InstanceQuota');
