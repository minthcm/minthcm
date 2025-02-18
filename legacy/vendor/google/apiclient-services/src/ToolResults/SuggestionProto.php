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

namespace Google\Service\ToolResults;

class SuggestionProto extends \Google\Model
{
  /**
   * @var string
   */
  public $helpUrl;
  protected $longMessageType = SafeHtmlProto::class;
  protected $longMessageDataType = '';
  /**
   * @var string
   */
  public $priority;
  /**
   * @var string
   */
  public $pseudoResourceId;
  protected $regionType = RegionProto::class;
  protected $regionDataType = '';
  /**
   * @var string
   */
  public $resourceName;
  /**
   * @var string
   */
  public $screenId;
  public $secondaryPriority;
  protected $shortMessageType = SafeHtmlProto::class;
  protected $shortMessageDataType = '';
  /**
   * @var string
   */
  public $title;

  /**
   * @param string
   */
  public function setHelpUrl($helpUrl)
  {
    $this->helpUrl = $helpUrl;
  }
  /**
   * @return string
   */
  public function getHelpUrl()
  {
    return $this->helpUrl;
  }
  /**
   * @param SafeHtmlProto
   */
  public function setLongMessage(SafeHtmlProto $longMessage)
  {
    $this->longMessage = $longMessage;
  }
  /**
   * @return SafeHtmlProto
   */
  public function getLongMessage()
  {
    return $this->longMessage;
  }
  /**
   * @param string
   */
  public function setPriority($priority)
  {
    $this->priority = $priority;
  }
  /**
   * @return string
   */
  public function getPriority()
  {
    return $this->priority;
  }
  /**
   * @param string
   */
  public function setPseudoResourceId($pseudoResourceId)
  {
    $this->pseudoResourceId = $pseudoResourceId;
  }
  /**
   * @return string
   */
  public function getPseudoResourceId()
  {
    return $this->pseudoResourceId;
  }
  /**
   * @param RegionProto
   */
  public function setRegion(RegionProto $region)
  {
    $this->region = $region;
  }
  /**
   * @return RegionProto
   */
  public function getRegion()
  {
    return $this->region;
  }
  /**
   * @param string
   */
  public function setResourceName($resourceName)
  {
    $this->resourceName = $resourceName;
  }
  /**
   * @return string
   */
  public function getResourceName()
  {
    return $this->resourceName;
  }
  /**
   * @param string
   */
  public function setScreenId($screenId)
  {
    $this->screenId = $screenId;
  }
  /**
   * @return string
   */
  public function getScreenId()
  {
    return $this->screenId;
  }
  public function setSecondaryPriority($secondaryPriority)
  {
    $this->secondaryPriority = $secondaryPriority;
  }
  public function getSecondaryPriority()
  {
    return $this->secondaryPriority;
  }
  /**
   * @param SafeHtmlProto
   */
  public function setShortMessage(SafeHtmlProto $shortMessage)
  {
    $this->shortMessage = $shortMessage;
  }
  /**
   * @return SafeHtmlProto
   */
  public function getShortMessage()
  {
    return $this->shortMessage;
  }
  /**
   * @param string
   */
  public function setTitle($title)
  {
    $this->title = $title;
  }
  /**
   * @return string
   */
  public function getTitle()
  {
    return $this->title;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SuggestionProto::class, 'Google_Service_ToolResults_SuggestionProto');
