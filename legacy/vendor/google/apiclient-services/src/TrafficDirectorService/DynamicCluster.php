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

namespace Google\Service\TrafficDirectorService;

class DynamicCluster extends \Google\Model
{
  /**
   * @var string
   */
  public $clientStatus;
  /**
   * @var array[]
   */
  public $cluster;
  protected $errorStateType = UpdateFailureState::class;
  protected $errorStateDataType = '';
  /**
   * @var string
   */
  public $lastUpdated;
  /**
   * @var string
   */
  public $versionInfo;

  /**
   * @param string
   */
  public function setClientStatus($clientStatus)
  {
    $this->clientStatus = $clientStatus;
  }
  /**
   * @return string
   */
  public function getClientStatus()
  {
    return $this->clientStatus;
  }
  /**
   * @param array[]
   */
  public function setCluster($cluster)
  {
    $this->cluster = $cluster;
  }
  /**
   * @return array[]
   */
  public function getCluster()
  {
    return $this->cluster;
  }
  /**
   * @param UpdateFailureState
   */
  public function setErrorState(UpdateFailureState $errorState)
  {
    $this->errorState = $errorState;
  }
  /**
   * @return UpdateFailureState
   */
  public function getErrorState()
  {
    return $this->errorState;
  }
  /**
   * @param string
   */
  public function setLastUpdated($lastUpdated)
  {
    $this->lastUpdated = $lastUpdated;
  }
  /**
   * @return string
   */
  public function getLastUpdated()
  {
    return $this->lastUpdated;
  }
  /**
   * @param string
   */
  public function setVersionInfo($versionInfo)
  {
    $this->versionInfo = $versionInfo;
  }
  /**
   * @return string
   */
  public function getVersionInfo()
  {
    return $this->versionInfo;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DynamicCluster::class, 'Google_Service_TrafficDirectorService_DynamicCluster');
