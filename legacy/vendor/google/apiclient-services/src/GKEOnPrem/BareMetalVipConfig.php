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

class BareMetalVipConfig extends \Google\Model
{
  /**
   * @var string
   */
  public $controlPlaneVip;
  /**
   * @var string
   */
  public $ingressVip;

  /**
   * @param string
   */
  public function setControlPlaneVip($controlPlaneVip)
  {
    $this->controlPlaneVip = $controlPlaneVip;
  }
  /**
   * @return string
   */
  public function getControlPlaneVip()
  {
    return $this->controlPlaneVip;
  }
  /**
   * @param string
   */
  public function setIngressVip($ingressVip)
  {
    $this->ingressVip = $ingressVip;
  }
  /**
   * @return string
   */
  public function getIngressVip()
  {
    return $this->ingressVip;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(BareMetalVipConfig::class, 'Google_Service_GKEOnPrem_BareMetalVipConfig');
