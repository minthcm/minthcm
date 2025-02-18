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

namespace Google\Service\CloudBuild;

class ArtifactResult extends \Google\Collection
{
  protected $collection_key = 'fileHash';
  protected $fileHashType = FileHashes::class;
  protected $fileHashDataType = 'array';
  /**
   * @var string
   */
  public $location;

  /**
   * @param FileHashes[]
   */
  public function setFileHash($fileHash)
  {
    $this->fileHash = $fileHash;
  }
  /**
   * @return FileHashes[]
   */
  public function getFileHash()
  {
    return $this->fileHash;
  }
  /**
   * @param string
   */
  public function setLocation($location)
  {
    $this->location = $location;
  }
  /**
   * @return string
   */
  public function getLocation()
  {
    return $this->location;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ArtifactResult::class, 'Google_Service_CloudBuild_ArtifactResult');
