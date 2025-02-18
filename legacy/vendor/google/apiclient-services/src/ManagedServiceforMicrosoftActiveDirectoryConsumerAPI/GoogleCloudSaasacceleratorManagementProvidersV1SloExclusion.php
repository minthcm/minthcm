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

namespace Google\Service\ManagedServiceforMicrosoftActiveDirectoryConsumerAPI;

class GoogleCloudSaasacceleratorManagementProvidersV1SloExclusion extends \Google\Model
{
  public $duration;
  public $reason;
  public $sliName;
  public $startTime;

  public function setDuration($duration)
  {
    $this->duration = $duration;
  }
  public function getDuration()
  {
    return $this->duration;
  }
  public function setReason($reason)
  {
    $this->reason = $reason;
  }
  public function getReason()
  {
    return $this->reason;
  }
  public function setSliName($sliName)
  {
    $this->sliName = $sliName;
  }
  public function getSliName()
  {
    return $this->sliName;
  }
  public function setStartTime($startTime)
  {
    $this->startTime = $startTime;
  }
  public function getStartTime()
  {
    return $this->startTime;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudSaasacceleratorManagementProvidersV1SloExclusion::class, 'Google_Service_ManagedServiceforMicrosoftActiveDirectoryConsumerAPI_GoogleCloudSaasacceleratorManagementProvidersV1SloExclusion');
