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

namespace Google\Service\Drive;

class ContentRestriction extends \Google\Model
{
  /**
   * @var bool
   */
  public $ownerRestricted;
  /**
   * @var bool
   */
  public $readOnly;
  /**
   * @var string
   */
  public $reason;
  protected $restrictingUserType = User::class;
  protected $restrictingUserDataType = '';
  /**
   * @var string
   */
  public $restrictionTime;
  /**
   * @var bool
   */
  public $systemRestricted;
  /**
   * @var string
   */
  public $type;

  /**
   * @param bool
   */
  public function setOwnerRestricted($ownerRestricted)
  {
    $this->ownerRestricted = $ownerRestricted;
  }
  /**
   * @return bool
   */
  public function getOwnerRestricted()
  {
    return $this->ownerRestricted;
  }
  /**
   * @param bool
   */
  public function setReadOnly($readOnly)
  {
    $this->readOnly = $readOnly;
  }
  /**
   * @return bool
   */
  public function getReadOnly()
  {
    return $this->readOnly;
  }
  /**
   * @param string
   */
  public function setReason($reason)
  {
    $this->reason = $reason;
  }
  /**
   * @return string
   */
  public function getReason()
  {
    return $this->reason;
  }
  /**
   * @param User
   */
  public function setRestrictingUser(User $restrictingUser)
  {
    $this->restrictingUser = $restrictingUser;
  }
  /**
   * @return User
   */
  public function getRestrictingUser()
  {
    return $this->restrictingUser;
  }
  /**
   * @param string
   */
  public function setRestrictionTime($restrictionTime)
  {
    $this->restrictionTime = $restrictionTime;
  }
  /**
   * @return string
   */
  public function getRestrictionTime()
  {
    return $this->restrictionTime;
  }
  /**
   * @param bool
   */
  public function setSystemRestricted($systemRestricted)
  {
    $this->systemRestricted = $systemRestricted;
  }
  /**
   * @return bool
   */
  public function getSystemRestricted()
  {
    return $this->systemRestricted;
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
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ContentRestriction::class, 'Google_Service_Drive_ContentRestriction');
