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

namespace Google\Service\GKEHub;

class CommonFeatureState extends \Google\Model
{
  protected $appdevexperienceType = AppDevExperienceFeatureState::class;
  protected $appdevexperienceDataType = '';
  protected $clusterupgradeType = ClusterUpgradeFleetState::class;
  protected $clusterupgradeDataType = '';
  protected $fleetobservabilityType = FleetObservabilityFeatureState::class;
  protected $fleetobservabilityDataType = '';
  protected $stateType = FeatureState::class;
  protected $stateDataType = '';

  /**
   * @param AppDevExperienceFeatureState
   */
  public function setAppdevexperience(AppDevExperienceFeatureState $appdevexperience)
  {
    $this->appdevexperience = $appdevexperience;
  }
  /**
   * @return AppDevExperienceFeatureState
   */
  public function getAppdevexperience()
  {
    return $this->appdevexperience;
  }
  /**
   * @param ClusterUpgradeFleetState
   */
  public function setClusterupgrade(ClusterUpgradeFleetState $clusterupgrade)
  {
    $this->clusterupgrade = $clusterupgrade;
  }
  /**
   * @return ClusterUpgradeFleetState
   */
  public function getClusterupgrade()
  {
    return $this->clusterupgrade;
  }
  /**
   * @param FleetObservabilityFeatureState
   */
  public function setFleetobservability(FleetObservabilityFeatureState $fleetobservability)
  {
    $this->fleetobservability = $fleetobservability;
  }
  /**
   * @return FleetObservabilityFeatureState
   */
  public function getFleetobservability()
  {
    return $this->fleetobservability;
  }
  /**
   * @param FeatureState
   */
  public function setState(FeatureState $state)
  {
    $this->state = $state;
  }
  /**
   * @return FeatureState
   */
  public function getState()
  {
    return $this->state;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CommonFeatureState::class, 'Google_Service_GKEHub_CommonFeatureState');
