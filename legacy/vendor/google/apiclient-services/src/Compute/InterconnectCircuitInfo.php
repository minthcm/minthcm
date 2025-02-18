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

namespace Google\Service\Compute;

class InterconnectCircuitInfo extends \Google\Model
{
  /**
   * @var string
   */
  public $customerDemarcId;
  /**
   * @var string
   */
  public $googleCircuitId;
  /**
   * @var string
   */
  public $googleDemarcId;

  /**
   * @param string
   */
  public function setCustomerDemarcId($customerDemarcId)
  {
    $this->customerDemarcId = $customerDemarcId;
  }
  /**
   * @return string
   */
  public function getCustomerDemarcId()
  {
    return $this->customerDemarcId;
  }
  /**
   * @param string
   */
  public function setGoogleCircuitId($googleCircuitId)
  {
    $this->googleCircuitId = $googleCircuitId;
  }
  /**
   * @return string
   */
  public function getGoogleCircuitId()
  {
    return $this->googleCircuitId;
  }
  /**
   * @param string
   */
  public function setGoogleDemarcId($googleDemarcId)
  {
    $this->googleDemarcId = $googleDemarcId;
  }
  /**
   * @return string
   */
  public function getGoogleDemarcId()
  {
    return $this->googleDemarcId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(InterconnectCircuitInfo::class, 'Google_Service_Compute_InterconnectCircuitInfo');
