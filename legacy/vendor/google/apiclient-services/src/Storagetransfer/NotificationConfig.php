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

namespace Google\Service\Storagetransfer;

class NotificationConfig extends \Google\Collection
{
  protected $collection_key = 'eventTypes';
  /**
   * @var string[]
   */
  public $eventTypes;
  /**
   * @var string
   */
  public $payloadFormat;
  /**
   * @var string
   */
  public $pubsubTopic;

  /**
   * @param string[]
   */
  public function setEventTypes($eventTypes)
  {
    $this->eventTypes = $eventTypes;
  }
  /**
   * @return string[]
   */
  public function getEventTypes()
  {
    return $this->eventTypes;
  }
  /**
   * @param string
   */
  public function setPayloadFormat($payloadFormat)
  {
    $this->payloadFormat = $payloadFormat;
  }
  /**
   * @return string
   */
  public function getPayloadFormat()
  {
    return $this->payloadFormat;
  }
  /**
   * @param string
   */
  public function setPubsubTopic($pubsubTopic)
  {
    $this->pubsubTopic = $pubsubTopic;
  }
  /**
   * @return string
   */
  public function getPubsubTopic()
  {
    return $this->pubsubTopic;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(NotificationConfig::class, 'Google_Service_Storagetransfer_NotificationConfig');
