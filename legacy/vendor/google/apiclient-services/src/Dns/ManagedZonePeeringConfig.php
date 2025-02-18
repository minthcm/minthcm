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

namespace Google\Service\Dns;

class ManagedZonePeeringConfig extends \Google\Model
{
  /**
   * @var string
   */
  public $kind;
  protected $targetNetworkType = ManagedZonePeeringConfigTargetNetwork::class;
  protected $targetNetworkDataType = '';

  /**
   * @param string
   */
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  /**
   * @return string
   */
  public function getKind()
  {
    return $this->kind;
  }
  /**
   * @param ManagedZonePeeringConfigTargetNetwork
   */
  public function setTargetNetwork(ManagedZonePeeringConfigTargetNetwork $targetNetwork)
  {
    $this->targetNetwork = $targetNetwork;
  }
  /**
   * @return ManagedZonePeeringConfigTargetNetwork
   */
  public function getTargetNetwork()
  {
    return $this->targetNetwork;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ManagedZonePeeringConfig::class, 'Google_Service_Dns_ManagedZonePeeringConfig');
