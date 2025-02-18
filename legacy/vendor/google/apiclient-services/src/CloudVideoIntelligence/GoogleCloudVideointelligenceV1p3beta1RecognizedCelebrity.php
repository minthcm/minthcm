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

namespace Google\Service\CloudVideoIntelligence;

class GoogleCloudVideointelligenceV1p3beta1RecognizedCelebrity extends \Google\Model
{
  protected $celebrityType = GoogleCloudVideointelligenceV1p3beta1Celebrity::class;
  protected $celebrityDataType = '';
  /**
   * @var float
   */
  public $confidence;

  /**
   * @param GoogleCloudVideointelligenceV1p3beta1Celebrity
   */
  public function setCelebrity(GoogleCloudVideointelligenceV1p3beta1Celebrity $celebrity)
  {
    $this->celebrity = $celebrity;
  }
  /**
   * @return GoogleCloudVideointelligenceV1p3beta1Celebrity
   */
  public function getCelebrity()
  {
    return $this->celebrity;
  }
  /**
   * @param float
   */
  public function setConfidence($confidence)
  {
    $this->confidence = $confidence;
  }
  /**
   * @return float
   */
  public function getConfidence()
  {
    return $this->confidence;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudVideointelligenceV1p3beta1RecognizedCelebrity::class, 'Google_Service_CloudVideoIntelligence_GoogleCloudVideointelligenceV1p3beta1RecognizedCelebrity');
