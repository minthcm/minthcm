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

namespace Google\Service\RecaptchaEnterprise;

class GoogleCloudRecaptchaenterpriseV1SearchRelatedAccountGroupMembershipsRequest extends \Google\Model
{
  /**
   * @var string
   */
  public $accountId;
  /**
   * @var string
   */
  public $hashedAccountId;
  /**
   * @var int
   */
  public $pageSize;
  /**
   * @var string
   */
  public $pageToken;

  /**
   * @param string
   */
  public function setAccountId($accountId)
  {
    $this->accountId = $accountId;
  }
  /**
   * @return string
   */
  public function getAccountId()
  {
    return $this->accountId;
  }
  /**
   * @param string
   */
  public function setHashedAccountId($hashedAccountId)
  {
    $this->hashedAccountId = $hashedAccountId;
  }
  /**
   * @return string
   */
  public function getHashedAccountId()
  {
    return $this->hashedAccountId;
  }
  /**
   * @param int
   */
  public function setPageSize($pageSize)
  {
    $this->pageSize = $pageSize;
  }
  /**
   * @return int
   */
  public function getPageSize()
  {
    return $this->pageSize;
  }
  /**
   * @param string
   */
  public function setPageToken($pageToken)
  {
    $this->pageToken = $pageToken;
  }
  /**
   * @return string
   */
  public function getPageToken()
  {
    return $this->pageToken;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudRecaptchaenterpriseV1SearchRelatedAccountGroupMembershipsRequest::class, 'Google_Service_RecaptchaEnterprise_GoogleCloudRecaptchaenterpriseV1SearchRelatedAccountGroupMembershipsRequest');
