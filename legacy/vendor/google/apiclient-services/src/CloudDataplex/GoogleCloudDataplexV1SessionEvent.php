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

namespace Google\Service\CloudDataplex;

class GoogleCloudDataplexV1SessionEvent extends \Google\Model
{
  /**
   * @var bool
   */
  public $eventSucceeded;
  /**
   * @var bool
   */
  public $fastStartupEnabled;
  /**
   * @var string
   */
  public $message;
  protected $queryType = GoogleCloudDataplexV1SessionEventQueryDetail::class;
  protected $queryDataType = '';
  /**
   * @var string
   */
  public $sessionId;
  /**
   * @var string
   */
  public $type;
  /**
   * @var string
   */
  public $unassignedDuration;
  /**
   * @var string
   */
  public $userId;

  /**
   * @param bool
   */
  public function setEventSucceeded($eventSucceeded)
  {
    $this->eventSucceeded = $eventSucceeded;
  }
  /**
   * @return bool
   */
  public function getEventSucceeded()
  {
    return $this->eventSucceeded;
  }
  /**
   * @param bool
   */
  public function setFastStartupEnabled($fastStartupEnabled)
  {
    $this->fastStartupEnabled = $fastStartupEnabled;
  }
  /**
   * @return bool
   */
  public function getFastStartupEnabled()
  {
    return $this->fastStartupEnabled;
  }
  /**
   * @param string
   */
  public function setMessage($message)
  {
    $this->message = $message;
  }
  /**
   * @return string
   */
  public function getMessage()
  {
    return $this->message;
  }
  /**
   * @param GoogleCloudDataplexV1SessionEventQueryDetail
   */
  public function setQuery(GoogleCloudDataplexV1SessionEventQueryDetail $query)
  {
    $this->query = $query;
  }
  /**
   * @return GoogleCloudDataplexV1SessionEventQueryDetail
   */
  public function getQuery()
  {
    return $this->query;
  }
  /**
   * @param string
   */
  public function setSessionId($sessionId)
  {
    $this->sessionId = $sessionId;
  }
  /**
   * @return string
   */
  public function getSessionId()
  {
    return $this->sessionId;
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
  /**
   * @param string
   */
  public function setUnassignedDuration($unassignedDuration)
  {
    $this->unassignedDuration = $unassignedDuration;
  }
  /**
   * @return string
   */
  public function getUnassignedDuration()
  {
    return $this->unassignedDuration;
  }
  /**
   * @param string
   */
  public function setUserId($userId)
  {
    $this->userId = $userId;
  }
  /**
   * @return string
   */
  public function getUserId()
  {
    return $this->userId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDataplexV1SessionEvent::class, 'Google_Service_CloudDataplex_GoogleCloudDataplexV1SessionEvent');
