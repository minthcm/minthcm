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

namespace Google\Service\OSConfig;

class OSPolicyResourceFile extends \Google\Model
{
  /**
   * @var bool
   */
  public $allowInsecure;
  protected $gcsType = OSPolicyResourceFileGcs::class;
  protected $gcsDataType = '';
  /**
   * @var string
   */
  public $localPath;
  protected $remoteType = OSPolicyResourceFileRemote::class;
  protected $remoteDataType = '';

  /**
   * @param bool
   */
  public function setAllowInsecure($allowInsecure)
  {
    $this->allowInsecure = $allowInsecure;
  }
  /**
   * @return bool
   */
  public function getAllowInsecure()
  {
    return $this->allowInsecure;
  }
  /**
   * @param OSPolicyResourceFileGcs
   */
  public function setGcs(OSPolicyResourceFileGcs $gcs)
  {
    $this->gcs = $gcs;
  }
  /**
   * @return OSPolicyResourceFileGcs
   */
  public function getGcs()
  {
    return $this->gcs;
  }
  /**
   * @param string
   */
  public function setLocalPath($localPath)
  {
    $this->localPath = $localPath;
  }
  /**
   * @return string
   */
  public function getLocalPath()
  {
    return $this->localPath;
  }
  /**
   * @param OSPolicyResourceFileRemote
   */
  public function setRemote(OSPolicyResourceFileRemote $remote)
  {
    $this->remote = $remote;
  }
  /**
   * @return OSPolicyResourceFileRemote
   */
  public function getRemote()
  {
    return $this->remote;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(OSPolicyResourceFile::class, 'Google_Service_OSConfig_OSPolicyResourceFile');
