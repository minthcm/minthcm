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

class BareMetalAdminControlPlaneConfig extends \Google\Collection
{
  protected $collection_key = 'apiServerArgs';
  protected $apiServerArgsType = BareMetalAdminApiServerArgument::class;
  protected $apiServerArgsDataType = 'array';
  protected $controlPlaneNodePoolConfigType = BareMetalAdminControlPlaneNodePoolConfig::class;
  protected $controlPlaneNodePoolConfigDataType = '';

  /**
   * @param BareMetalAdminApiServerArgument[]
   */
  public function setApiServerArgs($apiServerArgs)
  {
    $this->apiServerArgs = $apiServerArgs;
  }
  /**
   * @return BareMetalAdminApiServerArgument[]
   */
  public function getApiServerArgs()
  {
    return $this->apiServerArgs;
  }
  /**
   * @param BareMetalAdminControlPlaneNodePoolConfig
   */
  public function setControlPlaneNodePoolConfig(BareMetalAdminControlPlaneNodePoolConfig $controlPlaneNodePoolConfig)
  {
    $this->controlPlaneNodePoolConfig = $controlPlaneNodePoolConfig;
  }
  /**
   * @return BareMetalAdminControlPlaneNodePoolConfig
   */
  public function getControlPlaneNodePoolConfig()
  {
    return $this->controlPlaneNodePoolConfig;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(BareMetalAdminControlPlaneConfig::class, 'Google_Service_GKEOnPrem_BareMetalAdminControlPlaneConfig');
