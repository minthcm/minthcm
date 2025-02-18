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

class MessageInfo extends \Google\Model
{
  /**
   * @var string
   */
  public $authorUserType;
  protected $messageType = Message::class;
  protected $messageDataType = '';
  /**
   * @var string
   */
  public $searcherMembershipState;

  /**
   * @param string
   */
  public function setAuthorUserType($authorUserType)
  {
    $this->authorUserType = $authorUserType;
  }
  /**
   * @return string
   */
  public function getAuthorUserType()
  {
    return $this->authorUserType;
  }
  /**
   * @param Message
   */
  public function setMessage(Message $message)
  {
    $this->message = $message;
  }
  /**
   * @return Message
   */
  public function getMessage()
  {
    return $this->message;
  }
  /**
   * @param string
   */
  public function setSearcherMembershipState($searcherMembershipState)
  {
    $this->searcherMembershipState = $searcherMembershipState;
  }
  /**
   * @return string
   */
  public function getSearcherMembershipState()
  {
    return $this->searcherMembershipState;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(MessageInfo::class, 'Google_Service_CloudSearch_MessageInfo');
