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

namespace Google\Service\DriveActivity;

class User extends \Google\Model
{
  protected $deletedUserType = DeletedUser::class;
  protected $deletedUserDataType = '';
  protected $knownUserType = KnownUser::class;
  protected $knownUserDataType = '';
  protected $unknownUserType = UnknownUser::class;
  protected $unknownUserDataType = '';

  /**
   * @param DeletedUser
   */
  public function setDeletedUser(DeletedUser $deletedUser)
  {
    $this->deletedUser = $deletedUser;
  }
  /**
   * @return DeletedUser
   */
  public function getDeletedUser()
  {
    return $this->deletedUser;
  }
  /**
   * @param KnownUser
   */
  public function setKnownUser(KnownUser $knownUser)
  {
    $this->knownUser = $knownUser;
  }
  /**
   * @return KnownUser
   */
  public function getKnownUser()
  {
    return $this->knownUser;
  }
  /**
   * @param UnknownUser
   */
  public function setUnknownUser(UnknownUser $unknownUser)
  {
    $this->unknownUser = $unknownUser;
  }
  /**
   * @return UnknownUser
   */
  public function getUnknownUser()
  {
    return $this->unknownUser;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(User::class, 'Google_Service_DriveActivity_User');
