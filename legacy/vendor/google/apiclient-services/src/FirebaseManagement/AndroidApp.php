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

namespace Google\Service\FirebaseManagement;

class AndroidApp extends \Google\Collection
{
  protected $collection_key = 'sha256Hashes';
  /**
   * @var string
   */
  public $apiKeyId;
  /**
   * @var string
   */
  public $appId;
  /**
   * @var string
   */
  public $displayName;
  /**
   * @var string
   */
  public $etag;
  /**
   * @var string
   */
  public $expireTime;
  /**
   * @var string
   */
  public $name;
  /**
   * @var string
   */
  public $packageName;
  /**
   * @var string
   */
  public $projectId;
  /**
   * @var string[]
   */
  public $sha1Hashes;
  /**
   * @var string[]
   */
  public $sha256Hashes;
  /**
   * @var string
   */
  public $state;

  /**
   * @param string
   */
  public function setApiKeyId($apiKeyId)
  {
    $this->apiKeyId = $apiKeyId;
  }
  /**
   * @return string
   */
  public function getApiKeyId()
  {
    return $this->apiKeyId;
  }
  /**
   * @param string
   */
  public function setAppId($appId)
  {
    $this->appId = $appId;
  }
  /**
   * @return string
   */
  public function getAppId()
  {
    return $this->appId;
  }
  /**
   * @param string
   */
  public function setDisplayName($displayName)
  {
    $this->displayName = $displayName;
  }
  /**
   * @return string
   */
  public function getDisplayName()
  {
    return $this->displayName;
  }
  /**
   * @param string
   */
  public function setEtag($etag)
  {
    $this->etag = $etag;
  }
  /**
   * @return string
   */
  public function getEtag()
  {
    return $this->etag;
  }
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
   * @param string
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
  /**
   * @param string
   */
  public function setPackageName($packageName)
  {
    $this->packageName = $packageName;
  }
  /**
   * @return string
   */
  public function getPackageName()
  {
    return $this->packageName;
  }
  /**
   * @param string
   */
  public function setProjectId($projectId)
  {
    $this->projectId = $projectId;
  }
  /**
   * @return string
   */
  public function getProjectId()
  {
    return $this->projectId;
  }
  /**
   * @param string[]
   */
  public function setSha1Hashes($sha1Hashes)
  {
    $this->sha1Hashes = $sha1Hashes;
  }
  /**
   * @return string[]
   */
  public function getSha1Hashes()
  {
    return $this->sha1Hashes;
  }
  /**
   * @param string[]
   */
  public function setSha256Hashes($sha256Hashes)
  {
    $this->sha256Hashes = $sha256Hashes;
  }
  /**
   * @return string[]
   */
  public function getSha256Hashes()
  {
    return $this->sha256Hashes;
  }
  /**
   * @param string
   */
  public function setState($state)
  {
    $this->state = $state;
  }
  /**
   * @return string
   */
  public function getState()
  {
    return $this->state;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AndroidApp::class, 'Google_Service_FirebaseManagement_AndroidApp');
