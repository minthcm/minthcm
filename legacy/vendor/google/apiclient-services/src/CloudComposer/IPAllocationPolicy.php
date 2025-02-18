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

namespace Google\Service\CloudComposer;

class IPAllocationPolicy extends \Google\Model
{
  /**
   * @var string
   */
  public $clusterIpv4CidrBlock;
  /**
   * @var string
   */
  public $clusterSecondaryRangeName;
  /**
   * @var string
   */
  public $servicesIpv4CidrBlock;
  /**
   * @var string
   */
  public $servicesSecondaryRangeName;
  /**
   * @var bool
   */
  public $useIpAliases;

  /**
   * @param string
   */
  public function setClusterIpv4CidrBlock($clusterIpv4CidrBlock)
  {
    $this->clusterIpv4CidrBlock = $clusterIpv4CidrBlock;
  }
  /**
   * @return string
   */
  public function getClusterIpv4CidrBlock()
  {
    return $this->clusterIpv4CidrBlock;
  }
  /**
   * @param string
   */
  public function setClusterSecondaryRangeName($clusterSecondaryRangeName)
  {
    $this->clusterSecondaryRangeName = $clusterSecondaryRangeName;
  }
  /**
   * @return string
   */
  public function getClusterSecondaryRangeName()
  {
    return $this->clusterSecondaryRangeName;
  }
  /**
   * @param string
   */
  public function setServicesIpv4CidrBlock($servicesIpv4CidrBlock)
  {
    $this->servicesIpv4CidrBlock = $servicesIpv4CidrBlock;
  }
  /**
   * @return string
   */
  public function getServicesIpv4CidrBlock()
  {
    return $this->servicesIpv4CidrBlock;
  }
  /**
   * @param string
   */
  public function setServicesSecondaryRangeName($servicesSecondaryRangeName)
  {
    $this->servicesSecondaryRangeName = $servicesSecondaryRangeName;
  }
  /**
   * @return string
   */
  public function getServicesSecondaryRangeName()
  {
    return $this->servicesSecondaryRangeName;
  }
  /**
   * @param bool
   */
  public function setUseIpAliases($useIpAliases)
  {
    $this->useIpAliases = $useIpAliases;
  }
  /**
   * @return bool
   */
  public function getUseIpAliases()
  {
    return $this->useIpAliases;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(IPAllocationPolicy::class, 'Google_Service_CloudComposer_IPAllocationPolicy');
