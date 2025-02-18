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

namespace Google\Service\Aiplatform;

class GoogleCloudAiplatformV1SchemaPredictPredictionVideoActionRecognitionPredictionResult extends \Google\Model
{
  /**
   * @var float
   */
  public $confidence;
  /**
   * @var string
   */
  public $displayName;
  /**
   * @var string
   */
  public $id;
  /**
   * @var string
   */
  public $timeSegmentEnd;
  /**
   * @var string
   */
  public $timeSegmentStart;

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
  /**
   * @param string
   */
  public function setDisplayName($displayName)
  {
    $this->displayName = $displayName;
  }
  /**
   * @return string
   */
  public function getDisplayName()
  {
    return $this->displayName;
  }
  /**
   * @param string
   */
  public function setId($id)
  {
    $this->id = $id;
  }
  /**
   * @return string
   */
  public function getId()
  {
    return $this->id;
  }
  /**
   * @param string
   */
  public function setTimeSegmentEnd($timeSegmentEnd)
  {
    $this->timeSegmentEnd = $timeSegmentEnd;
  }
  /**
   * @return string
   */
  public function getTimeSegmentEnd()
  {
    return $this->timeSegmentEnd;
  }
  /**
   * @param string
   */
  public function setTimeSegmentStart($timeSegmentStart)
  {
    $this->timeSegmentStart = $timeSegmentStart;
  }
  /**
   * @return string
   */
  public function getTimeSegmentStart()
  {
    return $this->timeSegmentStart;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAiplatformV1SchemaPredictPredictionVideoActionRecognitionPredictionResult::class, 'Google_Service_Aiplatform_GoogleCloudAiplatformV1SchemaPredictPredictionVideoActionRecognitionPredictionResult');
