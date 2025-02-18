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

namespace Google\Service\SA360;

class GoogleAdsSearchads360V0ResourcesConversionActionFloodlightSettings extends \Google\Model
{
  /**
   * @var string
   */
  public $activityGroupTag;
  /**
   * @var string
   */
  public $activityId;
  /**
   * @var string
   */
  public $activityTag;

  /**
   * @param string
   */
  public function setActivityGroupTag($activityGroupTag)
  {
    $this->activityGroupTag = $activityGroupTag;
  }
  /**
   * @return string
   */
  public function getActivityGroupTag()
  {
    return $this->activityGroupTag;
  }
  /**
   * @param string
   */
  public function setActivityId($activityId)
  {
    $this->activityId = $activityId;
  }
  /**
   * @return string
   */
  public function getActivityId()
  {
    return $this->activityId;
  }
  /**
   * @param string
   */
  public function setActivityTag($activityTag)
  {
    $this->activityTag = $activityTag;
  }
  /**
   * @return string
   */
  public function getActivityTag()
  {
    return $this->activityTag;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleAdsSearchads360V0ResourcesConversionActionFloodlightSettings::class, 'Google_Service_SA360_GoogleAdsSearchads360V0ResourcesConversionActionFloodlightSettings');
