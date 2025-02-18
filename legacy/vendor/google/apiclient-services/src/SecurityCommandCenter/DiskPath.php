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

namespace Google\Service\SecurityCommandCenter;

class DiskPath extends \Google\Model
{
  /**
   * @var string
   */
  public $partitionUuid;
  /**
   * @var string
   */
  public $relativePath;

  /**
   * @param string
   */
  public function setPartitionUuid($partitionUuid)
  {
    $this->partitionUuid = $partitionUuid;
  }
  /**
   * @return string
   */
  public function getPartitionUuid()
  {
    return $this->partitionUuid;
  }
  /**
   * @param string
   */
  public function setRelativePath($relativePath)
  {
    $this->relativePath = $relativePath;
  }
  /**
   * @return string
   */
  public function getRelativePath()
  {
    return $this->relativePath;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DiskPath::class, 'Google_Service_SecurityCommandCenter_DiskPath');
