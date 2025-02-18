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

namespace Google\Service\Storagetransfer;

class UpdateTransferJobRequest extends \Google\Model
{
  /**
   * @var string
   */
  public $projectId;
  protected $transferJobType = TransferJob::class;
  protected $transferJobDataType = '';
  /**
   * @var string
   */
  public $updateTransferJobFieldMask;

  /**
   * @param string
   */
  public function setProjectId($projectId)
  {
    $this->projectId = $projectId;
  }
  /**
   * @return string
   */
  public function getProjectId()
  {
    return $this->projectId;
  }
  /**
   * @param TransferJob
   */
  public function setTransferJob(TransferJob $transferJob)
  {
    $this->transferJob = $transferJob;
  }
  /**
   * @return TransferJob
   */
  public function getTransferJob()
  {
    return $this->transferJob;
  }
  /**
   * @param string
   */
  public function setUpdateTransferJobFieldMask($updateTransferJobFieldMask)
  {
    $this->updateTransferJobFieldMask = $updateTransferJobFieldMask;
  }
  /**
   * @return string
   */
  public function getUpdateTransferJobFieldMask()
  {
    return $this->updateTransferJobFieldMask;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(UpdateTransferJobRequest::class, 'Google_Service_Storagetransfer_UpdateTransferJobRequest');
