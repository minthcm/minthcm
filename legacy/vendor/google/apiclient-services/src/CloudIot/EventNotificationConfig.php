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

namespace Google\Service\CloudIot;

class EventNotificationConfig extends \Google\Model
{
  /**
   * @var string
   */
  public $pubsubTopicName;
  /**
   * @var string
   */
  public $subfolderMatches;

  /**
   * @param string
   */
  public function setPubsubTopicName($pubsubTopicName)
  {
    $this->pubsubTopicName = $pubsubTopicName;
  }
  /**
   * @return string
   */
  public function getPubsubTopicName()
  {
    return $this->pubsubTopicName;
  }
  /**
   * @param string
   */
  public function setSubfolderMatches($subfolderMatches)
  {
    $this->subfolderMatches = $subfolderMatches;
  }
  /**
   * @return string
   */
  public function getSubfolderMatches()
  {
    return $this->subfolderMatches;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(EventNotificationConfig::class, 'Google_Service_CloudIot_EventNotificationConfig');
