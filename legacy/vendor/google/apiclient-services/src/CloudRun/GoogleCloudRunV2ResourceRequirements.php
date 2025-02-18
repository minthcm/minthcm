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

namespace Google\Service\CloudRun;

class GoogleCloudRunV2ResourceRequirements extends \Google\Model
{
  /**
   * @var bool
   */
  public $cpuIdle;
  /**
   * @var string[]
   */
  public $limits;
  /**
   * @var bool
   */
  public $startupCpuBoost;

  /**
   * @param bool
   */
  public function setCpuIdle($cpuIdle)
  {
    $this->cpuIdle = $cpuIdle;
  }
  /**
   * @return bool
   */
  public function getCpuIdle()
  {
    return $this->cpuIdle;
  }
  /**
   * @param string[]
   */
  public function setLimits($limits)
  {
    $this->limits = $limits;
  }
  /**
   * @return string[]
   */
  public function getLimits()
  {
    return $this->limits;
  }
  /**
   * @param bool
   */
  public function setStartupCpuBoost($startupCpuBoost)
  {
    $this->startupCpuBoost = $startupCpuBoost;
  }
  /**
   * @return bool
   */
  public function getStartupCpuBoost()
  {
    return $this->startupCpuBoost;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudRunV2ResourceRequirements::class, 'Google_Service_CloudRun_GoogleCloudRunV2ResourceRequirements');
