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

namespace Google\Service\AccessApproval;

class SignatureInfo extends \Google\Model
{
  /**
   * @var string
   */
  public $customerKmsKeyVersion;
  /**
   * @var string
   */
  public $googleKeyAlgorithm;
  /**
   * @var string
   */
  public $googlePublicKeyPem;
  /**
   * @var string
   */
  public $serializedApprovalRequest;
  /**
   * @var string
   */
  public $signature;

  /**
   * @param string
   */
  public function setCustomerKmsKeyVersion($customerKmsKeyVersion)
  {
    $this->customerKmsKeyVersion = $customerKmsKeyVersion;
  }
  /**
   * @return string
   */
  public function getCustomerKmsKeyVersion()
  {
    return $this->customerKmsKeyVersion;
  }
  /**
   * @param string
   */
  public function setGoogleKeyAlgorithm($googleKeyAlgorithm)
  {
    $this->googleKeyAlgorithm = $googleKeyAlgorithm;
  }
  /**
   * @return string
   */
  public function getGoogleKeyAlgorithm()
  {
    return $this->googleKeyAlgorithm;
  }
  /**
   * @param string
   */
  public function setGooglePublicKeyPem($googlePublicKeyPem)
  {
    $this->googlePublicKeyPem = $googlePublicKeyPem;
  }
  /**
   * @return string
   */
  public function getGooglePublicKeyPem()
  {
    return $this->googlePublicKeyPem;
  }
  /**
   * @param string
   */
  public function setSerializedApprovalRequest($serializedApprovalRequest)
  {
    $this->serializedApprovalRequest = $serializedApprovalRequest;
  }
  /**
   * @return string
   */
  public function getSerializedApprovalRequest()
  {
    return $this->serializedApprovalRequest;
  }
  /**
   * @param string
   */
  public function setSignature($signature)
  {
    $this->signature = $signature;
  }
  /**
   * @return string
   */
  public function getSignature()
  {
    return $this->signature;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SignatureInfo::class, 'Google_Service_AccessApproval_SignatureInfo');
