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

namespace Google\Service\CertificateAuthorityService;

class ActivateCertificateAuthorityRequest extends \Google\Model
{
  /**
   * @var string
   */
  public $pemCaCertificate;
  /**
   * @var string
   */
  public $requestId;
  protected $subordinateConfigType = SubordinateConfig::class;
  protected $subordinateConfigDataType = '';

  /**
   * @param string
   */
  public function setPemCaCertificate($pemCaCertificate)
  {
    $this->pemCaCertificate = $pemCaCertificate;
  }
  /**
   * @return string
   */
  public function getPemCaCertificate()
  {
    return $this->pemCaCertificate;
  }
  /**
   * @param string
   */
  public function setRequestId($requestId)
  {
    $this->requestId = $requestId;
  }
  /**
   * @return string
   */
  public function getRequestId()
  {
    return $this->requestId;
  }
  /**
   * @param SubordinateConfig
   */
  public function setSubordinateConfig(SubordinateConfig $subordinateConfig)
  {
    $this->subordinateConfig = $subordinateConfig;
  }
  /**
   * @return SubordinateConfig
   */
  public function getSubordinateConfig()
  {
    return $this->subordinateConfig;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ActivateCertificateAuthorityRequest::class, 'Google_Service_CertificateAuthorityService_ActivateCertificateAuthorityRequest');
