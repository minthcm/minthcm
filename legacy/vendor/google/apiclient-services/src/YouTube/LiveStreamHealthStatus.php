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

namespace Google\Service\YouTube;

class LiveStreamHealthStatus extends \Google\Collection
{
  protected $collection_key = 'configurationIssues';
  protected $configurationIssuesType = LiveStreamConfigurationIssue::class;
  protected $configurationIssuesDataType = 'array';
  /**
   * @var string
   */
  public $lastUpdateTimeSeconds;
  /**
   * @var string
   */
  public $status;

  /**
   * @param LiveStreamConfigurationIssue[]
   */
  public function setConfigurationIssues($configurationIssues)
  {
    $this->configurationIssues = $configurationIssues;
  }
  /**
   * @return LiveStreamConfigurationIssue[]
   */
  public function getConfigurationIssues()
  {
    return $this->configurationIssues;
  }
  /**
   * @param string
   */
  public function setLastUpdateTimeSeconds($lastUpdateTimeSeconds)
  {
    $this->lastUpdateTimeSeconds = $lastUpdateTimeSeconds;
  }
  /**
   * @return string
   */
  public function getLastUpdateTimeSeconds()
  {
    return $this->lastUpdateTimeSeconds;
  }
  /**
   * @param string
   */
  public function setStatus($status)
  {
    $this->status = $status;
  }
  /**
   * @return string
   */
  public function getStatus()
  {
    return $this->status;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(LiveStreamHealthStatus::class, 'Google_Service_YouTube_LiveStreamHealthStatus');
