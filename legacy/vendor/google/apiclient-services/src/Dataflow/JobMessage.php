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

namespace Google\Service\Dataflow;

class JobMessage extends \Google\Model
{
  /**
   * @var string
   */
  public $id;
  /**
   * @var string
   */
  public $messageImportance;
  /**
   * @var string
   */
  public $messageText;
  /**
   * @var string
   */
  public $time;

  /**
   * @param string
   */
  public function setId($id)
  {
    $this->id = $id;
  }
  /**
   * @return string
   */
  public function getId()
  {
    return $this->id;
  }
  /**
   * @param string
   */
  public function setMessageImportance($messageImportance)
  {
    $this->messageImportance = $messageImportance;
  }
  /**
   * @return string
   */
  public function getMessageImportance()
  {
    return $this->messageImportance;
  }
  /**
   * @param string
   */
  public function setMessageText($messageText)
  {
    $this->messageText = $messageText;
  }
  /**
   * @return string
   */
  public function getMessageText()
  {
    return $this->messageText;
  }
  /**
   * @param string
   */
  public function setTime($time)
  {
    $this->time = $time;
  }
  /**
   * @return string
   */
  public function getTime()
  {
    return $this->time;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(JobMessage::class, 'Google_Service_Dataflow_JobMessage');
