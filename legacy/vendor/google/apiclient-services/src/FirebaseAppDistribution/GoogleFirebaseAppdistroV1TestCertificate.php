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

namespace Google\Service\FirebaseAppDistribution;

class GoogleFirebaseAppdistroV1TestCertificate extends \Google\Model
{
  /**
   * @var string
   */
  public $hashMd5;
  /**
   * @var string
   */
  public $hashSha1;
  /**
   * @var string
   */
  public $hashSha256;

  /**
   * @param string
   */
  public function setHashMd5($hashMd5)
  {
    $this->hashMd5 = $hashMd5;
  }
  /**
   * @return string
   */
  public function getHashMd5()
  {
    return $this->hashMd5;
  }
  /**
   * @param string
   */
  public function setHashSha1($hashSha1)
  {
    $this->hashSha1 = $hashSha1;
  }
  /**
   * @return string
   */
  public function getHashSha1()
  {
    return $this->hashSha1;
  }
  /**
   * @param string
   */
  public function setHashSha256($hashSha256)
  {
    $this->hashSha256 = $hashSha256;
  }
  /**
   * @return string
   */
  public function getHashSha256()
  {
    return $this->hashSha256;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleFirebaseAppdistroV1TestCertificate::class, 'Google_Service_FirebaseAppDistribution_GoogleFirebaseAppdistroV1TestCertificate');
