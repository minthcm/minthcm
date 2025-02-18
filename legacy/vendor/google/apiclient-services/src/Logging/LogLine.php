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

namespace Google\Service\Logging;

class LogLine extends \Google\Model
{
  /**
   * @var string
   */
  public $logMessage;
  /**
   * @var string
   */
  public $severity;
  protected $sourceLocationType = SourceLocation::class;
  protected $sourceLocationDataType = '';
  /**
   * @var string
   */
  public $time;

  /**
   * @param string
   */
  public function setLogMessage($logMessage)
  {
    $this->logMessage = $logMessage;
  }
  /**
   * @return string
   */
  public function getLogMessage()
  {
    return $this->logMessage;
  }
  /**
   * @param string
   */
  public function setSeverity($severity)
  {
    $this->severity = $severity;
  }
  /**
   * @return string
   */
  public function getSeverity()
  {
    return $this->severity;
  }
  /**
   * @param SourceLocation
   */
  public function setSourceLocation(SourceLocation $sourceLocation)
  {
    $this->sourceLocation = $sourceLocation;
  }
  /**
   * @return SourceLocation
   */
  public function getSourceLocation()
  {
    return $this->sourceLocation;
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
class_alias(LogLine::class, 'Google_Service_Logging_LogLine');
