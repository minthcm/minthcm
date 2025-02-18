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

namespace Google\Service\MigrationCenterAPI;

class UploadFileInfo extends \Google\Model
{
  /**
   * @var string[]
   */
  public $headers;
  /**
   * @var string
   */
  public $signedUri;
  /**
   * @var string
   */
  public $uriExpirationTime;

  /**
   * @param string[]
   */
  public function setHeaders($headers)
  {
    $this->headers = $headers;
  }
  /**
   * @return string[]
   */
  public function getHeaders()
  {
    return $this->headers;
  }
  /**
   * @param string
   */
  public function setSignedUri($signedUri)
  {
    $this->signedUri = $signedUri;
  }
  /**
   * @return string
   */
  public function getSignedUri()
  {
    return $this->signedUri;
  }
  /**
   * @param string
   */
  public function setUriExpirationTime($uriExpirationTime)
  {
    $this->uriExpirationTime = $uriExpirationTime;
  }
  /**
   * @return string
   */
  public function getUriExpirationTime()
  {
    return $this->uriExpirationTime;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(UploadFileInfo::class, 'Google_Service_MigrationCenterAPI_UploadFileInfo');
