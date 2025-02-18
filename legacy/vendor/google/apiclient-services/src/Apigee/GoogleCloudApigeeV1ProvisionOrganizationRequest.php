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

namespace Google\Service\Apigee;

class GoogleCloudApigeeV1ProvisionOrganizationRequest extends \Google\Model
{
  /**
   * @var string
   */
  public $analyticsRegion;
  /**
   * @var string
   */
  public $authorizedNetwork;
  /**
   * @var bool
   */
  public $disableVpcPeering;
  /**
   * @var string
   */
  public $runtimeLocation;

  /**
   * @param string
   */
  public function setAnalyticsRegion($analyticsRegion)
  {
    $this->analyticsRegion = $analyticsRegion;
  }
  /**
   * @return string
   */
  public function getAnalyticsRegion()
  {
    return $this->analyticsRegion;
  }
  /**
   * @param string
   */
  public function setAuthorizedNetwork($authorizedNetwork)
  {
    $this->authorizedNetwork = $authorizedNetwork;
  }
  /**
   * @return string
   */
  public function getAuthorizedNetwork()
  {
    return $this->authorizedNetwork;
  }
  /**
   * @param bool
   */
  public function setDisableVpcPeering($disableVpcPeering)
  {
    $this->disableVpcPeering = $disableVpcPeering;
  }
  /**
   * @return bool
   */
  public function getDisableVpcPeering()
  {
    return $this->disableVpcPeering;
  }
  /**
   * @param string
   */
  public function setRuntimeLocation($runtimeLocation)
  {
    $this->runtimeLocation = $runtimeLocation;
  }
  /**
   * @return string
   */
  public function getRuntimeLocation()
  {
    return $this->runtimeLocation;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudApigeeV1ProvisionOrganizationRequest::class, 'Google_Service_Apigee_GoogleCloudApigeeV1ProvisionOrganizationRequest');
