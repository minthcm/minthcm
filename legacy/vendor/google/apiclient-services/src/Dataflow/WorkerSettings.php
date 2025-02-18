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

class WorkerSettings extends \Google\Model
{
  /**
   * @var string
   */
  public $baseUrl;
  /**
   * @var bool
   */
  public $reportingEnabled;
  /**
   * @var string
   */
  public $servicePath;
  /**
   * @var string
   */
  public $shuffleServicePath;
  /**
   * @var string
   */
  public $tempStoragePrefix;
  /**
   * @var string
   */
  public $workerId;

  /**
   * @param string
   */
  public function setBaseUrl($baseUrl)
  {
    $this->baseUrl = $baseUrl;
  }
  /**
   * @return string
   */
  public function getBaseUrl()
  {
    return $this->baseUrl;
  }
  /**
   * @param bool
   */
  public function setReportingEnabled($reportingEnabled)
  {
    $this->reportingEnabled = $reportingEnabled;
  }
  /**
   * @return bool
   */
  public function getReportingEnabled()
  {
    return $this->reportingEnabled;
  }
  /**
   * @param string
   */
  public function setServicePath($servicePath)
  {
    $this->servicePath = $servicePath;
  }
  /**
   * @return string
   */
  public function getServicePath()
  {
    return $this->servicePath;
  }
  /**
   * @param string
   */
  public function setShuffleServicePath($shuffleServicePath)
  {
    $this->shuffleServicePath = $shuffleServicePath;
  }
  /**
   * @return string
   */
  public function getShuffleServicePath()
  {
    return $this->shuffleServicePath;
  }
  /**
   * @param string
   */
  public function setTempStoragePrefix($tempStoragePrefix)
  {
    $this->tempStoragePrefix = $tempStoragePrefix;
  }
  /**
   * @return string
   */
  public function getTempStoragePrefix()
  {
    return $this->tempStoragePrefix;
  }
  /**
   * @param string
   */
  public function setWorkerId($workerId)
  {
    $this->workerId = $workerId;
  }
  /**
   * @return string
   */
  public function getWorkerId()
  {
    return $this->workerId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(WorkerSettings::class, 'Google_Service_Dataflow_WorkerSettings');
