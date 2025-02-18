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

class BucketSoftDeletePolicy extends \Google\Model
{
  /**
   * @var string
   */
  public $effectiveTime;
  /**
   * @var string
   */
  public $retentionDurationSeconds;

  /**
   * @param string
   */
  public function setEffectiveTime($effectiveTime)
  {
    $this->effectiveTime = $effectiveTime;
  }
  /**
   * @return string
   */
  public function getEffectiveTime()
  {
    return $this->effectiveTime;
  }
  /**
   * @param string
   */
  public function setRetentionDurationSeconds($retentionDurationSeconds)
  {
    $this->retentionDurationSeconds = $retentionDurationSeconds;
  }
  /**
   * @return string
   */
  public function getRetentionDurationSeconds()
  {
    return $this->retentionDurationSeconds;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(BucketSoftDeletePolicy::class, 'Google_Service_Storage_BucketSoftDeletePolicy');
