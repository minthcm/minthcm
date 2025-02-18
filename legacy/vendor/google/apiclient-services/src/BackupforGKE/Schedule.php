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

namespace Google\Service\BackupforGKE;

class Schedule extends \Google\Model
{
  /**
   * @var string
   */
  public $cronSchedule;
  /**
   * @var string
   */
  public $nextScheduledBackupTime;
  /**
   * @var bool
   */
  public $paused;
  protected $rpoConfigType = RpoConfig::class;
  protected $rpoConfigDataType = '';

  /**
   * @param string
   */
  public function setCronSchedule($cronSchedule)
  {
    $this->cronSchedule = $cronSchedule;
  }
  /**
   * @return string
   */
  public function getCronSchedule()
  {
    return $this->cronSchedule;
  }
  /**
   * @param string
   */
  public function setNextScheduledBackupTime($nextScheduledBackupTime)
  {
    $this->nextScheduledBackupTime = $nextScheduledBackupTime;
  }
  /**
   * @return string
   */
  public function getNextScheduledBackupTime()
  {
    return $this->nextScheduledBackupTime;
  }
  /**
   * @param bool
   */
  public function setPaused($paused)
  {
    $this->paused = $paused;
  }
  /**
   * @return bool
   */
  public function getPaused()
  {
    return $this->paused;
  }
  /**
   * @param RpoConfig
   */
  public function setRpoConfig(RpoConfig $rpoConfig)
  {
    $this->rpoConfig = $rpoConfig;
  }
  /**
   * @return RpoConfig
   */
  public function getRpoConfig()
  {
    return $this->rpoConfig;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Schedule::class, 'Google_Service_BackupforGKE_Schedule');
