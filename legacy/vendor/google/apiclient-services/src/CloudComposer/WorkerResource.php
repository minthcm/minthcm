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

namespace Google\Service\CloudComposer;

class WorkerResource extends \Google\Model
{
  /**
   * @var float
   */
  public $cpu;
  /**
   * @var int
   */
  public $maxCount;
  /**
   * @var float
   */
  public $memoryGb;
  /**
   * @var int
   */
  public $minCount;
  /**
   * @var float
   */
  public $storageGb;

  /**
   * @param float
   */
  public function setCpu($cpu)
  {
    $this->cpu = $cpu;
  }
  /**
   * @return float
   */
  public function getCpu()
  {
    return $this->cpu;
  }
  /**
   * @param int
   */
  public function setMaxCount($maxCount)
  {
    $this->maxCount = $maxCount;
  }
  /**
   * @return int
   */
  public function getMaxCount()
  {
    return $this->maxCount;
  }
  /**
   * @param float
   */
  public function setMemoryGb($memoryGb)
  {
    $this->memoryGb = $memoryGb;
  }
  /**
   * @return float
   */
  public function getMemoryGb()
  {
    return $this->memoryGb;
  }
  /**
   * @param int
   */
  public function setMinCount($minCount)
  {
    $this->minCount = $minCount;
  }
  /**
   * @return int
   */
  public function getMinCount()
  {
    return $this->minCount;
  }
  /**
   * @param float
   */
  public function setStorageGb($storageGb)
  {
    $this->storageGb = $storageGb;
  }
  /**
   * @return float
   */
  public function getStorageGb()
  {
    return $this->storageGb;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(WorkerResource::class, 'Google_Service_CloudComposer_WorkerResource');
