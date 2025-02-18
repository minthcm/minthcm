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

class GoogleFirebaseAppdistroV1UploadReleaseResponse extends \Google\Model
{
  protected $releaseType = GoogleFirebaseAppdistroV1Release::class;
  protected $releaseDataType = '';
  /**
   * @var string
   */
  public $result;

  /**
   * @param GoogleFirebaseAppdistroV1Release
   */
  public function setRelease(GoogleFirebaseAppdistroV1Release $release)
  {
    $this->release = $release;
  }
  /**
   * @return GoogleFirebaseAppdistroV1Release
   */
  public function getRelease()
  {
    return $this->release;
  }
  /**
   * @param string
   */
  public function setResult($result)
  {
    $this->result = $result;
  }
  /**
   * @return string
   */
  public function getResult()
  {
    return $this->result;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleFirebaseAppdistroV1UploadReleaseResponse::class, 'Google_Service_FirebaseAppDistribution_GoogleFirebaseAppdistroV1UploadReleaseResponse');
