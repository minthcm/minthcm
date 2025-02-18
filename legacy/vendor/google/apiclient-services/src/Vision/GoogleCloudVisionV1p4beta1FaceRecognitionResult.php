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

namespace Google\Service\Vision;

class GoogleCloudVisionV1p4beta1FaceRecognitionResult extends \Google\Model
{
  protected $celebrityType = GoogleCloudVisionV1p4beta1Celebrity::class;
  protected $celebrityDataType = '';
  /**
   * @var float
   */
  public $confidence;

  /**
   * @param GoogleCloudVisionV1p4beta1Celebrity
   */
  public function setCelebrity(GoogleCloudVisionV1p4beta1Celebrity $celebrity)
  {
    $this->celebrity = $celebrity;
  }
  /**
   * @return GoogleCloudVisionV1p4beta1Celebrity
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
class_alias(GoogleCloudVisionV1p4beta1FaceRecognitionResult::class, 'Google_Service_Vision_GoogleCloudVisionV1p4beta1FaceRecognitionResult');
