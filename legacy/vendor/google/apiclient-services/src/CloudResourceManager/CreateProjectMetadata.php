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

namespace Google\Service\CloudResourceManager;

class CreateProjectMetadata extends \Google\Model
{
  /**
   * @var string
   */
  public $createTime;
  /**
   * @var bool
   */
  public $gettable;
  /**
   * @var bool
   */
  public $ready;

  /**
   * @param string
   */
  public function setCreateTime($createTime)
  {
    $this->createTime = $createTime;
  }
  /**
   * @return string
   */
  public function getCreateTime()
  {
    return $this->createTime;
  }
  /**
   * @param bool
   */
  public function setGettable($gettable)
  {
    $this->gettable = $gettable;
  }
  /**
   * @return bool
   */
  public function getGettable()
  {
    return $this->gettable;
  }
  /**
   * @param bool
   */
  public function setReady($ready)
  {
    $this->ready = $ready;
  }
  /**
   * @return bool
   */
  public function getReady()
  {
    return $this->ready;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CreateProjectMetadata::class, 'Google_Service_CloudResourceManager_CreateProjectMetadata');
