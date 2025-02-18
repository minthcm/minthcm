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

class DirectoryChromeosdevicesCommand extends \Google\Model
{
  /**
   * @var string
   */
  public $commandExpireTime;
  /**
   * @var string
   */
  public $commandId;
  protected $commandResultType = DirectoryChromeosdevicesCommandResult::class;
  protected $commandResultDataType = '';
  /**
   * @var string
   */
  public $issueTime;
  /**
   * @var string
   */
  public $payload;
  /**
   * @var string
   */
  public $state;
  /**
   * @var string
   */
  public $type;

  /**
   * @param string
   */
  public function setCommandExpireTime($commandExpireTime)
  {
    $this->commandExpireTime = $commandExpireTime;
  }
  /**
   * @return string
   */
  public function getCommandExpireTime()
  {
    return $this->commandExpireTime;
  }
  /**
   * @param string
   */
  public function setCommandId($commandId)
  {
    $this->commandId = $commandId;
  }
  /**
   * @return string
   */
  public function getCommandId()
  {
    return $this->commandId;
  }
  /**
   * @param DirectoryChromeosdevicesCommandResult
   */
  public function setCommandResult(DirectoryChromeosdevicesCommandResult $commandResult)
  {
    $this->commandResult = $commandResult;
  }
  /**
   * @return DirectoryChromeosdevicesCommandResult
   */
  public function getCommandResult()
  {
    return $this->commandResult;
  }
  /**
   * @param string
   */
  public function setIssueTime($issueTime)
  {
    $this->issueTime = $issueTime;
  }
  /**
   * @return string
   */
  public function getIssueTime()
  {
    return $this->issueTime;
  }
  /**
   * @param string
   */
  public function setPayload($payload)
  {
    $this->payload = $payload;
  }
  /**
   * @return string
   */
  public function getPayload()
  {
    return $this->payload;
  }
  /**
   * @param string
   */
  public function setState($state)
  {
    $this->state = $state;
  }
  /**
   * @return string
   */
  public function getState()
  {
    return $this->state;
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
class_alias(DirectoryChromeosdevicesCommand::class, 'Google_Service_Directory_DirectoryChromeosdevicesCommand');
