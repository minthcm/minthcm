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

namespace Google\Service\GameServices;

class TargetDetails extends \Google\Collection
{
  protected $collection_key = 'fleetDetails';
  protected $fleetDetailsType = TargetFleetDetails::class;
  protected $fleetDetailsDataType = 'array';
  /**
   * @var string
   */
  public $gameServerClusterName;
  /**
   * @var string
   */
  public $gameServerDeploymentName;

  /**
   * @param TargetFleetDetails[]
   */
  public function setFleetDetails($fleetDetails)
  {
    $this->fleetDetails = $fleetDetails;
  }
  /**
   * @return TargetFleetDetails[]
   */
  public function getFleetDetails()
  {
    return $this->fleetDetails;
  }
  /**
   * @param string
   */
  public function setGameServerClusterName($gameServerClusterName)
  {
    $this->gameServerClusterName = $gameServerClusterName;
  }
  /**
   * @return string
   */
  public function getGameServerClusterName()
  {
    return $this->gameServerClusterName;
  }
  /**
   * @param string
   */
  public function setGameServerDeploymentName($gameServerDeploymentName)
  {
    $this->gameServerDeploymentName = $gameServerDeploymentName;
  }
  /**
   * @return string
   */
  public function getGameServerDeploymentName()
  {
    return $this->gameServerDeploymentName;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(TargetDetails::class, 'Google_Service_GameServices_TargetDetails');
