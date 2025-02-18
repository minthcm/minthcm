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

namespace Google\Service\CloudSearch;

class UserMentionMetadata extends \Google\Model
{
  /**
   * @var string
   */
  public $displayName;
  /**
   * @var string
   */
  public $gender;
  protected $idType = UserId::class;
  protected $idDataType = '';
  protected $inviteeInfoType = InviteeInfo::class;
  protected $inviteeInfoDataType = '';
  /**
   * @var string
   */
  public $type;
  /**
   * @var string
   */
  public $userMentionError;

  /**
   * @param string
   */
  public function setDisplayName($displayName)
  {
    $this->displayName = $displayName;
  }
  /**
   * @return string
   */
  public function getDisplayName()
  {
    return $this->displayName;
  }
  /**
   * @param string
   */
  public function setGender($gender)
  {
    $this->gender = $gender;
  }
  /**
   * @return string
   */
  public function getGender()
  {
    return $this->gender;
  }
  /**
   * @param UserId
   */
  public function setId(UserId $id)
  {
    $this->id = $id;
  }
  /**
   * @return UserId
   */
  public function getId()
  {
    return $this->id;
  }
  /**
   * @param InviteeInfo
   */
  public function setInviteeInfo(InviteeInfo $inviteeInfo)
  {
    $this->inviteeInfo = $inviteeInfo;
  }
  /**
   * @return InviteeInfo
   */
  public function getInviteeInfo()
  {
    return $this->inviteeInfo;
  }
  /**
   * @param string
   */
  public function setType($type)
  {
    $this->type = $type;
  }
  /**
   * @return string
   */
  public function getType()
  {
    return $this->type;
  }
  /**
   * @param string
   */
  public function setUserMentionError($userMentionError)
  {
    $this->userMentionError = $userMentionError;
  }
  /**
   * @return string
   */
  public function getUserMentionError()
  {
    return $this->userMentionError;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(UserMentionMetadata::class, 'Google_Service_CloudSearch_UserMentionMetadata');
