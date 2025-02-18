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

namespace Google\Service\Kmsinventory;

class GoogleCloudKmsV1KeyOperationAttestation extends \Google\Model
{
  protected $certChainsType = GoogleCloudKmsV1KeyOperationAttestationCertificateChains::class;
  protected $certChainsDataType = '';
  /**
   * @var string
   */
  public $content;
  /**
   * @var string
   */
  public $format;

  /**
   * @param GoogleCloudKmsV1KeyOperationAttestationCertificateChains
   */
  public function setCertChains(GoogleCloudKmsV1KeyOperationAttestationCertificateChains $certChains)
  {
    $this->certChains = $certChains;
  }
  /**
   * @return GoogleCloudKmsV1KeyOperationAttestationCertificateChains
   */
  public function getCertChains()
  {
    return $this->certChains;
  }
  /**
   * @param string
   */
  public function setContent($content)
  {
    $this->content = $content;
  }
  /**
   * @return string
   */
  public function getContent()
  {
    return $this->content;
  }
  /**
   * @param string
   */
  public function setFormat($format)
  {
    $this->format = $format;
  }
  /**
   * @return string
   */
  public function getFormat()
  {
    return $this->format;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudKmsV1KeyOperationAttestation::class, 'Google_Service_Kmsinventory_GoogleCloudKmsV1KeyOperationAttestation');
