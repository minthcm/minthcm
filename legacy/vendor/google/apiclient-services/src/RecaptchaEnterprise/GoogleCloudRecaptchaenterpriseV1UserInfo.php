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

class GoogleCloudRecaptchaenterpriseV1UserInfo extends \Google\Collection
{
  protected $collection_key = 'userIds';
  /**
   * @var string
   */
  public $accountId;
  /**
   * @var string
   */
  public $createAccountTime;
  protected $userIdsType = GoogleCloudRecaptchaenterpriseV1UserId::class;
  protected $userIdsDataType = 'array';

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
  public function setCreateAccountTime($createAccountTime)
  {
    $this->createAccountTime = $createAccountTime;
  }
  /**
   * @return string
   */
  public function getCreateAccountTime()
  {
    return $this->createAccountTime;
  }
  /**
   * @param GoogleCloudRecaptchaenterpriseV1UserId[]
   */
  public function setUserIds($userIds)
  {
    $this->userIds = $userIds;
  }
  /**
   * @return GoogleCloudRecaptchaenterpriseV1UserId[]
   */
  public function getUserIds()
  {
    return $this->userIds;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudRecaptchaenterpriseV1UserInfo::class, 'Google_Service_RecaptchaEnterprise_GoogleCloudRecaptchaenterpriseV1UserInfo');
