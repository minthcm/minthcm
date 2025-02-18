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

namespace Google\Service\CloudSearch;

class AppsDynamiteSharedMeetMetadata extends \Google\Model
{
  /**
   * @var string
   */
  public $meetingCode;
  /**
   * @var string
   */
  public $meetingType;
  /**
   * @var string
   */
  public $meetingUrl;

  /**
   * @param string
   */
  public function setMeetingCode($meetingCode)
  {
    $this->meetingCode = $meetingCode;
  }
  /**
   * @return string
   */
  public function getMeetingCode()
  {
    return $this->meetingCode;
  }
  /**
   * @param string
   */
  public function setMeetingType($meetingType)
  {
    $this->meetingType = $meetingType;
  }
  /**
   * @return string
   */
  public function getMeetingType()
  {
    return $this->meetingType;
  }
  /**
   * @param string
   */
  public function setMeetingUrl($meetingUrl)
  {
    $this->meetingUrl = $meetingUrl;
  }
  /**
   * @return string
   */
  public function getMeetingUrl()
  {
    return $this->meetingUrl;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AppsDynamiteSharedMeetMetadata::class, 'Google_Service_CloudSearch_AppsDynamiteSharedMeetMetadata');
