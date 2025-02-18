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

namespace Google\Service\Integrations;

class GoogleCloudIntegrationsV1alphaExecutionDetails extends \Google\Collection
{
  protected $collection_key = 'executionSnapshots';
  protected $attemptStatsType = GoogleCloudIntegrationsV1alphaAttemptStats::class;
  protected $attemptStatsDataType = 'array';
  /**
   * @var string
   */
  public $eventExecutionSnapshotsSize;
  protected $executionSnapshotsType = GoogleCloudIntegrationsV1alphaExecutionSnapshot::class;
  protected $executionSnapshotsDataType = 'array';
  /**
   * @var string
   */
  public $state;

  /**
   * @param GoogleCloudIntegrationsV1alphaAttemptStats[]
   */
  public function setAttemptStats($attemptStats)
  {
    $this->attemptStats = $attemptStats;
  }
  /**
   * @return GoogleCloudIntegrationsV1alphaAttemptStats[]
   */
  public function getAttemptStats()
  {
    return $this->attemptStats;
  }
  /**
   * @param string
   */
  public function setEventExecutionSnapshotsSize($eventExecutionSnapshotsSize)
  {
    $this->eventExecutionSnapshotsSize = $eventExecutionSnapshotsSize;
  }
  /**
   * @return string
   */
  public function getEventExecutionSnapshotsSize()
  {
    return $this->eventExecutionSnapshotsSize;
  }
  /**
   * @param GoogleCloudIntegrationsV1alphaExecutionSnapshot[]
   */
  public function setExecutionSnapshots($executionSnapshots)
  {
    $this->executionSnapshots = $executionSnapshots;
  }
  /**
   * @return GoogleCloudIntegrationsV1alphaExecutionSnapshot[]
   */
  public function getExecutionSnapshots()
  {
    return $this->executionSnapshots;
  }
  /**
   * @param string
   */
  public function setState($state)
  {
    $this->state = $state;
  }
  /**
   * @return string
   */
  public function getState()
  {
    return $this->state;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudIntegrationsV1alphaExecutionDetails::class, 'Google_Service_Integrations_GoogleCloudIntegrationsV1alphaExecutionDetails');
