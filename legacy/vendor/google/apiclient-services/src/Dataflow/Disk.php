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

namespace Google\Service\Dataflow;

class Disk extends \Google\Model
{
  /**
   * @var string
   */
  public $diskType;
  /**
   * @var string
   */
  public $mountPoint;
  /**
   * @var int
   */
  public $sizeGb;

  /**
   * @param string
   */
  public function setDiskType($diskType)
  {
    $this->diskType = $diskType;
  }
  /**
   * @return string
   */
  public function getDiskType()
  {
    return $this->diskType;
  }
  /**
   * @param string
   */
  public function setMountPoint($mountPoint)
  {
    $this->mountPoint = $mountPoint;
  }
  /**
   * @return string
   */
  public function getMountPoint()
  {
    return $this->mountPoint;
  }
  /**
   * @param int
   */
  public function setSizeGb($sizeGb)
  {
    $this->sizeGb = $sizeGb;
  }
  /**
   * @return int
   */
  public function getSizeGb()
  {
    return $this->sizeGb;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Disk::class, 'Google_Service_Dataflow_Disk');
