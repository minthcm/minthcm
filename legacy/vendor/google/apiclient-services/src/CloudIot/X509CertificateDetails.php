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

namespace Google\Service\CloudIot;

class X509CertificateDetails extends \Google\Model
{
  /**
   * @var string
   */
  public $expiryTime;
  /**
   * @var string
   */
  public $issuer;
  /**
   * @var string
   */
  public $publicKeyType;
  /**
   * @var string
   */
  public $signatureAlgorithm;
  /**
   * @var string
   */
  public $startTime;
  /**
   * @var string
   */
  public $subject;

  /**
   * @param string
   */
  public function setExpiryTime($expiryTime)
  {
    $this->expiryTime = $expiryTime;
  }
  /**
   * @return string
   */
  public function getExpiryTime()
  {
    return $this->expiryTime;
  }
  /**
   * @param string
   */
  public function setIssuer($issuer)
  {
    $this->issuer = $issuer;
  }
  /**
   * @return string
   */
  public function getIssuer()
  {
    return $this->issuer;
  }
  /**
   * @param string
   */
  public function setPublicKeyType($publicKeyType)
  {
    $this->publicKeyType = $publicKeyType;
  }
  /**
   * @return string
   */
  public function getPublicKeyType()
  {
    return $this->publicKeyType;
  }
  /**
   * @param string
   */
  public function setSignatureAlgorithm($signatureAlgorithm)
  {
    $this->signatureAlgorithm = $signatureAlgorithm;
  }
  /**
   * @return string
   */
  public function getSignatureAlgorithm()
  {
    return $this->signatureAlgorithm;
  }
  /**
   * @param string
   */
  public function setStartTime($startTime)
  {
    $this->startTime = $startTime;
  }
  /**
   * @return string
   */
  public function getStartTime()
  {
    return $this->startTime;
  }
  /**
   * @param string
   */
  public function setSubject($subject)
  {
    $this->subject = $subject;
  }
  /**
   * @return string
   */
  public function getSubject()
  {
    return $this->subject;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(X509CertificateDetails::class, 'Google_Service_CloudIot_X509CertificateDetails');
