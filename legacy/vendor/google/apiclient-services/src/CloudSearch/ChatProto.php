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

class ChatProto extends \Google\Model
{
  /**
   * @var string
   */
  public $chatId;
  /**
   * @var int
   */
  public $memberType;

  /**
   * @param string
   */
  public function setChatId($chatId)
  {
    $this->chatId = $chatId;
  }
  /**
   * @return string
   */
  public function getChatId()
  {
    return $this->chatId;
  }
  /**
   * @param int
   */
  public function setMemberType($memberType)
  {
    $this->memberType = $memberType;
  }
  /**
   * @return int
   */
  public function getMemberType()
  {
    return $this->memberType;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ChatProto::class, 'Google_Service_CloudSearch_ChatProto');
