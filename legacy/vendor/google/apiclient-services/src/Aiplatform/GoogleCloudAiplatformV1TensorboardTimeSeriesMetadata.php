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

class GoogleCloudAiplatformV1TensorboardTimeSeriesMetadata extends \Google\Model
{
  /**
   * @var string
   */
  public $maxBlobSequenceLength;
  /**
   * @var string
   */
  public $maxStep;
  /**
   * @var string
   */
  public $maxWallTime;

  /**
   * @param string
   */
  public function setMaxBlobSequenceLength($maxBlobSequenceLength)
  {
    $this->maxBlobSequenceLength = $maxBlobSequenceLength;
  }
  /**
   * @return string
   */
  public function getMaxBlobSequenceLength()
  {
    return $this->maxBlobSequenceLength;
  }
  /**
   * @param string
   */
  public function setMaxStep($maxStep)
  {
    $this->maxStep = $maxStep;
  }
  /**
   * @return string
   */
  public function getMaxStep()
  {
    return $this->maxStep;
  }
  /**
   * @param string
   */
  public function setMaxWallTime($maxWallTime)
  {
    $this->maxWallTime = $maxWallTime;
  }
  /**
   * @return string
   */
  public function getMaxWallTime()
  {
    return $this->maxWallTime;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAiplatformV1TensorboardTimeSeriesMetadata::class, 'Google_Service_Aiplatform_GoogleCloudAiplatformV1TensorboardTimeSeriesMetadata');
