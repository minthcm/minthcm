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

namespace Google\Service\StreetViewPublish;

class NoOverlapGpsFailureDetails extends \Google\Model
{
  /**
   * @var string
   */
  public $gpsEndTime;
  /**
   * @var string
   */
  public $gpsStartTime;
  /**
   * @var string
   */
  public $videoEndTime;
  /**
   * @var string
   */
  public $videoStartTime;

  /**
   * @param string
   */
  public function setGpsEndTime($gpsEndTime)
  {
    $this->gpsEndTime = $gpsEndTime;
  }
  /**
   * @return string
   */
  public function getGpsEndTime()
  {
    return $this->gpsEndTime;
  }
  /**
   * @param string
   */
  public function setGpsStartTime($gpsStartTime)
  {
    $this->gpsStartTime = $gpsStartTime;
  }
  /**
   * @return string
   */
  public function getGpsStartTime()
  {
    return $this->gpsStartTime;
  }
  /**
   * @param string
   */
  public function setVideoEndTime($videoEndTime)
  {
    $this->videoEndTime = $videoEndTime;
  }
  /**
   * @return string
   */
  public function getVideoEndTime()
  {
    return $this->videoEndTime;
  }
  /**
   * @param string
   */
  public function setVideoStartTime($videoStartTime)
  {
    $this->videoStartTime = $videoStartTime;
  }
  /**
   * @return string
   */
  public function getVideoStartTime()
  {
    return $this->videoStartTime;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(NoOverlapGpsFailureDetails::class, 'Google_Service_StreetViewPublish_NoOverlapGpsFailureDetails');
