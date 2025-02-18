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

namespace Google\Service\NetworkSecurity;

class MTLSPolicy extends \Google\Collection
{
  protected $collection_key = 'clientValidationCa';
  protected $clientValidationCaType = ValidationCA::class;
  protected $clientValidationCaDataType = 'array';
  /**
   * @var string
   */
  public $clientValidationMode;
  /**
   * @var string
   */
  public $clientValidationTrustConfig;

  /**
   * @param ValidationCA[]
   */
  public function setClientValidationCa($clientValidationCa)
  {
    $this->clientValidationCa = $clientValidationCa;
  }
  /**
   * @return ValidationCA[]
   */
  public function getClientValidationCa()
  {
    return $this->clientValidationCa;
  }
  /**
   * @param string
   */
  public function setClientValidationMode($clientValidationMode)
  {
    $this->clientValidationMode = $clientValidationMode;
  }
  /**
   * @return string
   */
  public function getClientValidationMode()
  {
    return $this->clientValidationMode;
  }
  /**
   * @param string
   */
  public function setClientValidationTrustConfig($clientValidationTrustConfig)
  {
    $this->clientValidationTrustConfig = $clientValidationTrustConfig;
  }
  /**
   * @return string
   */
  public function getClientValidationTrustConfig()
  {
    return $this->clientValidationTrustConfig;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(MTLSPolicy::class, 'Google_Service_NetworkSecurity_MTLSPolicy');
