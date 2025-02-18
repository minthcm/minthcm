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

class GoogleCloudVideointelligenceV1p2beta1DetectedLandmark extends \Google\Model
{
  /**
   * @var float
   */
  public $confidence;
  /**
   * @var string
   */
  public $name;
  protected $pointType = GoogleCloudVideointelligenceV1p2beta1NormalizedVertex::class;
  protected $pointDataType = '';

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
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
  /**
   * @param GoogleCloudVideointelligenceV1p2beta1NormalizedVertex
   */
  public function setPoint(GoogleCloudVideointelligenceV1p2beta1NormalizedVertex $point)
  {
    $this->point = $point;
  }
  /**
   * @return GoogleCloudVideointelligenceV1p2beta1NormalizedVertex
   */
  public function getPoint()
  {
    return $this->point;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudVideointelligenceV1p2beta1DetectedLandmark::class, 'Google_Service_CloudVideoIntelligence_GoogleCloudVideointelligenceV1p2beta1DetectedLandmark');
