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

namespace Google\Service\Speech;

class SpeakerDiarizationConfig extends \Google\Model
{
  /**
   * @var bool
   */
  public $enableSpeakerDiarization;
  /**
   * @var int
   */
  public $maxSpeakerCount;
  /**
   * @var int
   */
  public $minSpeakerCount;
  /**
   * @var int
   */
  public $speakerTag;

  /**
   * @param bool
   */
  public function setEnableSpeakerDiarization($enableSpeakerDiarization)
  {
    $this->enableSpeakerDiarization = $enableSpeakerDiarization;
  }
  /**
   * @return bool
   */
  public function getEnableSpeakerDiarization()
  {
    return $this->enableSpeakerDiarization;
  }
  /**
   * @param int
   */
  public function setMaxSpeakerCount($maxSpeakerCount)
  {
    $this->maxSpeakerCount = $maxSpeakerCount;
  }
  /**
   * @return int
   */
  public function getMaxSpeakerCount()
  {
    return $this->maxSpeakerCount;
  }
  /**
   * @param int
   */
  public function setMinSpeakerCount($minSpeakerCount)
  {
    $this->minSpeakerCount = $minSpeakerCount;
  }
  /**
   * @return int
   */
  public function getMinSpeakerCount()
  {
    return $this->minSpeakerCount;
  }
  /**
   * @param int
   */
  public function setSpeakerTag($speakerTag)
  {
    $this->speakerTag = $speakerTag;
  }
  /**
   * @return int
   */
  public function getSpeakerTag()
  {
    return $this->speakerTag;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SpeakerDiarizationConfig::class, 'Google_Service_Speech_SpeakerDiarizationConfig');
