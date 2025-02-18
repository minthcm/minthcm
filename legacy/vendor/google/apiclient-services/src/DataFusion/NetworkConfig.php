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

namespace Google\Service\DataFusion;

class NetworkConfig extends \Google\Model
{
  /**
   * @var string
   */
  public $connectionType;
  /**
   * @var string
   */
  public $ipAllocation;
  /**
   * @var string
   */
  public $network;
  protected $privateServiceConnectConfigType = PrivateServiceConnectConfig::class;
  protected $privateServiceConnectConfigDataType = '';

  /**
   * @param string
   */
  public function setConnectionType($connectionType)
  {
    $this->connectionType = $connectionType;
  }
  /**
   * @return string
   */
  public function getConnectionType()
  {
    return $this->connectionType;
  }
  /**
   * @param string
   */
  public function setIpAllocation($ipAllocation)
  {
    $this->ipAllocation = $ipAllocation;
  }
  /**
   * @return string
   */
  public function getIpAllocation()
  {
    return $this->ipAllocation;
  }
  /**
   * @param string
   */
  public function setNetwork($network)
  {
    $this->network = $network;
  }
  /**
   * @return string
   */
  public function getNetwork()
  {
    return $this->network;
  }
  /**
   * @param PrivateServiceConnectConfig
   */
  public function setPrivateServiceConnectConfig(PrivateServiceConnectConfig $privateServiceConnectConfig)
  {
    $this->privateServiceConnectConfig = $privateServiceConnectConfig;
  }
  /**
   * @return PrivateServiceConnectConfig
   */
  public function getPrivateServiceConnectConfig()
  {
    return $this->privateServiceConnectConfig;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(NetworkConfig::class, 'Google_Service_DataFusion_NetworkConfig');
