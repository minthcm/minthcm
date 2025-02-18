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

namespace Google\Service\AlertCenter;

class BatchUndeleteAlertsResponse extends \Google\Collection
{
  protected $collection_key = 'successAlertIds';
  protected $failedAlertStatusType = Status::class;
  protected $failedAlertStatusDataType = 'map';
  /**
   * @var string[]
   */
  public $successAlertIds;

  /**
   * @param Status[]
   */
  public function setFailedAlertStatus($failedAlertStatus)
  {
    $this->failedAlertStatus = $failedAlertStatus;
  }
  /**
   * @return Status[]
   */
  public function getFailedAlertStatus()
  {
    return $this->failedAlertStatus;
  }
  /**
   * @param string[]
   */
  public function setSuccessAlertIds($successAlertIds)
  {
    $this->successAlertIds = $successAlertIds;
  }
  /**
   * @return string[]
   */
  public function getSuccessAlertIds()
  {
    return $this->successAlertIds;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(BatchUndeleteAlertsResponse::class, 'Google_Service_AlertCenter_BatchUndeleteAlertsResponse');
