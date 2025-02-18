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

namespace Google\Service\SecurityCommandCenter;

class GoogleCloudSecuritycenterV2Indicator extends \Google\Collection
{
  protected $collection_key = 'uris';
  /**
   * @var string[]
   */
  public $domains;
  /**
   * @var string[]
   */
  public $ipAddresses;
  protected $signaturesType = GoogleCloudSecuritycenterV2ProcessSignature::class;
  protected $signaturesDataType = 'array';
  /**
   * @var string[]
   */
  public $uris;

  /**
   * @param string[]
   */
  public function setDomains($domains)
  {
    $this->domains = $domains;
  }
  /**
   * @return string[]
   */
  public function getDomains()
  {
    return $this->domains;
  }
  /**
   * @param string[]
   */
  public function setIpAddresses($ipAddresses)
  {
    $this->ipAddresses = $ipAddresses;
  }
  /**
   * @return string[]
   */
  public function getIpAddresses()
  {
    return $this->ipAddresses;
  }
  /**
   * @param GoogleCloudSecuritycenterV2ProcessSignature[]
   */
  public function setSignatures($signatures)
  {
    $this->signatures = $signatures;
  }
  /**
   * @return GoogleCloudSecuritycenterV2ProcessSignature[]
   */
  public function getSignatures()
  {
    return $this->signatures;
  }
  /**
   * @param string[]
   */
  public function setUris($uris)
  {
    $this->uris = $uris;
  }
  /**
   * @return string[]
   */
  public function getUris()
  {
    return $this->uris;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudSecuritycenterV2Indicator::class, 'Google_Service_SecurityCommandCenter_GoogleCloudSecuritycenterV2Indicator');
