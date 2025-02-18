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

namespace Google\Service\GKEOnPrem;

class BareMetalLoadBalancerConfig extends \Google\Model
{
  protected $bgpLbConfigType = BareMetalBgpLbConfig::class;
  protected $bgpLbConfigDataType = '';
  protected $manualLbConfigType = BareMetalManualLbConfig::class;
  protected $manualLbConfigDataType = '';
  protected $metalLbConfigType = BareMetalMetalLbConfig::class;
  protected $metalLbConfigDataType = '';
  protected $portConfigType = BareMetalPortConfig::class;
  protected $portConfigDataType = '';
  protected $vipConfigType = BareMetalVipConfig::class;
  protected $vipConfigDataType = '';

  /**
   * @param BareMetalBgpLbConfig
   */
  public function setBgpLbConfig(BareMetalBgpLbConfig $bgpLbConfig)
  {
    $this->bgpLbConfig = $bgpLbConfig;
  }
  /**
   * @return BareMetalBgpLbConfig
   */
  public function getBgpLbConfig()
  {
    return $this->bgpLbConfig;
  }
  /**
   * @param BareMetalManualLbConfig
   */
  public function setManualLbConfig(BareMetalManualLbConfig $manualLbConfig)
  {
    $this->manualLbConfig = $manualLbConfig;
  }
  /**
   * @return BareMetalManualLbConfig
   */
  public function getManualLbConfig()
  {
    return $this->manualLbConfig;
  }
  /**
   * @param BareMetalMetalLbConfig
   */
  public function setMetalLbConfig(BareMetalMetalLbConfig $metalLbConfig)
  {
    $this->metalLbConfig = $metalLbConfig;
  }
  /**
   * @return BareMetalMetalLbConfig
   */
  public function getMetalLbConfig()
  {
    return $this->metalLbConfig;
  }
  /**
   * @param BareMetalPortConfig
   */
  public function setPortConfig(BareMetalPortConfig $portConfig)
  {
    $this->portConfig = $portConfig;
  }
  /**
   * @return BareMetalPortConfig
   */
  public function getPortConfig()
  {
    return $this->portConfig;
  }
  /**
   * @param BareMetalVipConfig
   */
  public function setVipConfig(BareMetalVipConfig $vipConfig)
  {
    $this->vipConfig = $vipConfig;
  }
  /**
   * @return BareMetalVipConfig
   */
  public function getVipConfig()
  {
    return $this->vipConfig;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(BareMetalLoadBalancerConfig::class, 'Google_Service_GKEOnPrem_BareMetalLoadBalancerConfig');
