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

namespace Google\Service\VMMigrationService;

class MigrationError extends \Google\Collection
{
  protected $collection_key = 'helpLinks';
  protected $actionItemType = LocalizedMessage::class;
  protected $actionItemDataType = '';
  /**
   * @var string
   */
  public $code;
  protected $errorMessageType = LocalizedMessage::class;
  protected $errorMessageDataType = '';
  /**
   * @var string
   */
  public $errorTime;
  protected $helpLinksType = Link::class;
  protected $helpLinksDataType = 'array';

  /**
   * @param LocalizedMessage
   */
  public function setActionItem(LocalizedMessage $actionItem)
  {
    $this->actionItem = $actionItem;
  }
  /**
   * @return LocalizedMessage
   */
  public function getActionItem()
  {
    return $this->actionItem;
  }
  /**
   * @param string
   */
  public function setCode($code)
  {
    $this->code = $code;
  }
  /**
   * @return string
   */
  public function getCode()
  {
    return $this->code;
  }
  /**
   * @param LocalizedMessage
   */
  public function setErrorMessage(LocalizedMessage $errorMessage)
  {
    $this->errorMessage = $errorMessage;
  }
  /**
   * @return LocalizedMessage
   */
  public function getErrorMessage()
  {
    return $this->errorMessage;
  }
  /**
   * @param string
   */
  public function setErrorTime($errorTime)
  {
    $this->errorTime = $errorTime;
  }
  /**
   * @return string
   */
  public function getErrorTime()
  {
    return $this->errorTime;
  }
  /**
   * @param Link[]
   */
  public function setHelpLinks($helpLinks)
  {
    $this->helpLinks = $helpLinks;
  }
  /**
   * @return Link[]
   */
  public function getHelpLinks()
  {
    return $this->helpLinks;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(MigrationError::class, 'Google_Service_VMMigrationService_MigrationError');
