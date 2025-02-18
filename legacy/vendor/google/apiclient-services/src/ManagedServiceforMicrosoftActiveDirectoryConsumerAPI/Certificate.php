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

namespace Google\Service\ManagedServiceforMicrosoftActiveDirectoryConsumerAPI;

class Certificate extends \Google\Collection
{
  protected $collection_key = 'subjectAlternativeName';
  /**
   * @var string
   */
  public $expireTime;
  protected $issuingCertificateType = Certificate::class;
  protected $issuingCertificateDataType = '';
  /**
   * @var string
   */
  public $subject;
  /**
   * @var string[]
   */
  public $subjectAlternativeName;
  /**
   * @var string
   */
  public $thumbprint;

  /**
   * @param string
   */
  public function setExpireTime($expireTime)
  {
    $this->expireTime = $expireTime;
  }
  /**
   * @return string
   */
  public function getExpireTime()
  {
    return $this->expireTime;
  }
  /**
   * @param Certificate
   */
  public function setIssuingCertificate(Certificate $issuingCertificate)
  {
    $this->issuingCertificate = $issuingCertificate;
  }
  /**
   * @return Certificate
   */
  public function getIssuingCertificate()
  {
    return $this->issuingCertificate;
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
  /**
   * @param string[]
   */
  public function setSubjectAlternativeName($subjectAlternativeName)
  {
    $this->subjectAlternativeName = $subjectAlternativeName;
  }
  /**
   * @return string[]
   */
  public function getSubjectAlternativeName()
  {
    return $this->subjectAlternativeName;
  }
  /**
   * @param string
   */
  public function setThumbprint($thumbprint)
  {
    $this->thumbprint = $thumbprint;
  }
  /**
   * @return string
   */
  public function getThumbprint()
  {
    return $this->thumbprint;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Certificate::class, 'Google_Service_ManagedServiceforMicrosoftActiveDirectoryConsumerAPI_Certificate');
