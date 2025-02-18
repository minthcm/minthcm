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

namespace Google\Service\Assuredworkloads;

class GoogleCloudAssuredworkloadsV1ViolationExceptionContext extends \Google\Model
{
  /**
   * @var string
   */
  public $acknowledgementTime;
  /**
   * @var string
   */
  public $comment;
  /**
   * @var string
   */
  public $userName;

  /**
   * @param string
   */
  public function setAcknowledgementTime($acknowledgementTime)
  {
    $this->acknowledgementTime = $acknowledgementTime;
  }
  /**
   * @return string
   */
  public function getAcknowledgementTime()
  {
    return $this->acknowledgementTime;
  }
  /**
   * @param string
   */
  public function setComment($comment)
  {
    $this->comment = $comment;
  }
  /**
   * @return string
   */
  public function getComment()
  {
    return $this->comment;
  }
  /**
   * @param string
   */
  public function setUserName($userName)
  {
    $this->userName = $userName;
  }
  /**
   * @return string
   */
  public function getUserName()
  {
    return $this->userName;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAssuredworkloadsV1ViolationExceptionContext::class, 'Google_Service_Assuredworkloads_GoogleCloudAssuredworkloadsV1ViolationExceptionContext');
