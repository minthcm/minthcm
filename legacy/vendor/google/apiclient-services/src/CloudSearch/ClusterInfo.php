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

namespace Google\Service\CloudSearch;

class ClusterInfo extends \Google\Collection
{
  protected $collection_key = 'clusterId';
  /**
   * @var string[]
   */
  public $clusterId;
  /**
   * @var bool
   */
  public $throttled;

  /**
   * @param string[]
   */
  public function setClusterId($clusterId)
  {
    $this->clusterId = $clusterId;
  }
  /**
   * @return string[]
   */
  public function getClusterId()
  {
    return $this->clusterId;
  }
  /**
   * @param bool
   */
  public function setThrottled($throttled)
  {
    $this->throttled = $throttled;
  }
  /**
   * @return bool
   */
  public function getThrottled()
  {
    return $this->throttled;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ClusterInfo::class, 'Google_Service_CloudSearch_ClusterInfo');
