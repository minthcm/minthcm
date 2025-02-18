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

namespace Google\Service\Directory;

class DirectoryChromeosdevicesCommandResult extends \Google\Model
{
  /**
   * @var string
   */
  public $commandResultPayload;
  /**
   * @var string
   */
  public $errorMessage;
  /**
   * @var string
   */
  public $executeTime;
  /**
   * @var string
   */
  public $result;

  /**
   * @param string
   */
  public function setCommandResultPayload($commandResultPayload)
  {
    $this->commandResultPayload = $commandResultPayload;
  }
  /**
   * @return string
   */
  public function getCommandResultPayload()
  {
    return $this->commandResultPayload;
  }
  /**
   * @param string
   */
  public function setErrorMessage($errorMessage)
  {
    $this->errorMessage = $errorMessage;
  }
  /**
   * @return string
   */
  public function getErrorMessage()
  {
    return $this->errorMessage;
  }
  /**
   * @param string
   */
  public function setExecuteTime($executeTime)
  {
    $this->executeTime = $executeTime;
  }
  /**
   * @return string
   */
  public function getExecuteTime()
  {
    return $this->executeTime;
  }
  /**
   * @param string
   */
  public function setResult($result)
  {
    $this->result = $result;
  }
  /**
   * @return string
   */
  public function getResult()
  {
    return $this->result;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DirectoryChromeosdevicesCommandResult::class, 'Google_Service_Directory_DirectoryChromeosdevicesCommandResult');
