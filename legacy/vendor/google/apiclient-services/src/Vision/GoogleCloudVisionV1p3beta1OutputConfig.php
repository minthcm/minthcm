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

class GoogleCloudVisionV1p3beta1OutputConfig extends \Google\Model
{
  /**
   * @var int
   */
  public $batchSize;
  protected $gcsDestinationType = GoogleCloudVisionV1p3beta1GcsDestination::class;
  protected $gcsDestinationDataType = '';

  /**
   * @param int
   */
  public function setBatchSize($batchSize)
  {
    $this->batchSize = $batchSize;
  }
  /**
   * @return int
   */
  public function getBatchSize()
  {
    return $this->batchSize;
  }
  /**
   * @param GoogleCloudVisionV1p3beta1GcsDestination
   */
  public function setGcsDestination(GoogleCloudVisionV1p3beta1GcsDestination $gcsDestination)
  {
    $this->gcsDestination = $gcsDestination;
  }
  /**
   * @return GoogleCloudVisionV1p3beta1GcsDestination
   */
  public function getGcsDestination()
  {
    return $this->gcsDestination;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudVisionV1p3beta1OutputConfig::class, 'Google_Service_Vision_GoogleCloudVisionV1p3beta1OutputConfig');
