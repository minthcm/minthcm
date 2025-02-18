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

namespace Google\Service\Storage;

class BucketAutoclass extends \Google\Model
{
  /**
   * @var bool
   */
  public $enabled;
  /**
   * @var string
   */
  public $terminalStorageClass;
  /**
   * @var string
   */
  public $terminalStorageClassUpdateTime;
  /**
   * @var string
   */
  public $toggleTime;

  /**
   * @param bool
   */
  public function setEnabled($enabled)
  {
    $this->enabled = $enabled;
  }
  /**
   * @return bool
   */
  public function getEnabled()
  {
    return $this->enabled;
  }
  /**
   * @param string
   */
  public function setTerminalStorageClass($terminalStorageClass)
  {
    $this->terminalStorageClass = $terminalStorageClass;
  }
  /**
   * @return string
   */
  public function getTerminalStorageClass()
  {
    return $this->terminalStorageClass;
  }
  /**
   * @param string
   */
  public function setTerminalStorageClassUpdateTime($terminalStorageClassUpdateTime)
  {
    $this->terminalStorageClassUpdateTime = $terminalStorageClassUpdateTime;
  }
  /**
   * @return string
   */
  public function getTerminalStorageClassUpdateTime()
  {
    return $this->terminalStorageClassUpdateTime;
  }
  /**
   * @param string
   */
  public function setToggleTime($toggleTime)
  {
    $this->toggleTime = $toggleTime;
  }
  /**
   * @return string
   */
  public function getToggleTime()
  {
    return $this->toggleTime;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(BucketAutoclass::class, 'Google_Service_Storage_BucketAutoclass');
