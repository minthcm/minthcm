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

namespace Google\Service\Compute;

class ResourcePolicyInstanceSchedulePolicy extends \Google\Model
{
  /**
   * @var string
   */
  public $expirationTime;
  /**
   * @var string
   */
  public $startTime;
  /**
   * @var string
   */
  public $timeZone;
  protected $vmStartScheduleType = ResourcePolicyInstanceSchedulePolicySchedule::class;
  protected $vmStartScheduleDataType = '';
  protected $vmStopScheduleType = ResourcePolicyInstanceSchedulePolicySchedule::class;
  protected $vmStopScheduleDataType = '';

  /**
   * @param string
   */
  public function setExpirationTime($expirationTime)
  {
    $this->expirationTime = $expirationTime;
  }
  /**
   * @return string
   */
  public function getExpirationTime()
  {
    return $this->expirationTime;
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
  /**
   * @param string
   */
  public function setTimeZone($timeZone)
  {
    $this->timeZone = $timeZone;
  }
  /**
   * @return string
   */
  public function getTimeZone()
  {
    return $this->timeZone;
  }
  /**
   * @param ResourcePolicyInstanceSchedulePolicySchedule
   */
  public function setVmStartSchedule(ResourcePolicyInstanceSchedulePolicySchedule $vmStartSchedule)
  {
    $this->vmStartSchedule = $vmStartSchedule;
  }
  /**
   * @return ResourcePolicyInstanceSchedulePolicySchedule
   */
  public function getVmStartSchedule()
  {
    return $this->vmStartSchedule;
  }
  /**
   * @param ResourcePolicyInstanceSchedulePolicySchedule
   */
  public function setVmStopSchedule(ResourcePolicyInstanceSchedulePolicySchedule $vmStopSchedule)
  {
    $this->vmStopSchedule = $vmStopSchedule;
  }
  /**
   * @return ResourcePolicyInstanceSchedulePolicySchedule
   */
  public function getVmStopSchedule()
  {
    return $this->vmStopSchedule;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ResourcePolicyInstanceSchedulePolicy::class, 'Google_Service_Compute_ResourcePolicyInstanceSchedulePolicy');
