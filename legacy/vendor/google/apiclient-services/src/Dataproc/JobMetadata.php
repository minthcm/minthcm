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

namespace Google\Service\Dataproc;

class JobMetadata extends \Google\Model
{
  /**
   * @var string
   */
  public $jobId;
  /**
   * @var string
   */
  public $operationType;
  /**
   * @var string
   */
  public $startTime;
  protected $statusType = JobStatus::class;
  protected $statusDataType = '';

  /**
   * @param string
   */
  public function setJobId($jobId)
  {
    $this->jobId = $jobId;
  }
  /**
   * @return string
   */
  public function getJobId()
  {
    return $this->jobId;
  }
  /**
   * @param string
   */
  public function setOperationType($operationType)
  {
    $this->operationType = $operationType;
  }
  /**
   * @return string
   */
  public function getOperationType()
  {
    return $this->operationType;
  }
  /**
   * @param string
   */
  public function setStartTime($startTime)
  {
    $this->startTime = $startTime;
  }
  /**
   * @return string
   */
  public function getStartTime()
  {
    return $this->startTime;
  }
  /**
   * @param JobStatus
   */
  public function setStatus(JobStatus $status)
  {
    $this->status = $status;
  }
  /**
   * @return JobStatus
   */
  public function getStatus()
  {
    return $this->status;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(JobMetadata::class, 'Google_Service_Dataproc_JobMetadata');
