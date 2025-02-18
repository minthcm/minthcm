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

class GoogleCloudAiplatformV1FeaturestoreOnlineServingConfig extends \Google\Model
{
  /**
   * @var int
   */
  public $fixedNodeCount;
  protected $scalingType = GoogleCloudAiplatformV1FeaturestoreOnlineServingConfigScaling::class;
  protected $scalingDataType = '';

  /**
   * @param int
   */
  public function setFixedNodeCount($fixedNodeCount)
  {
    $this->fixedNodeCount = $fixedNodeCount;
  }
  /**
   * @return int
   */
  public function getFixedNodeCount()
  {
    return $this->fixedNodeCount;
  }
  /**
   * @param GoogleCloudAiplatformV1FeaturestoreOnlineServingConfigScaling
   */
  public function setScaling(GoogleCloudAiplatformV1FeaturestoreOnlineServingConfigScaling $scaling)
  {
    $this->scaling = $scaling;
  }
  /**
   * @return GoogleCloudAiplatformV1FeaturestoreOnlineServingConfigScaling
   */
  public function getScaling()
  {
    return $this->scaling;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAiplatformV1FeaturestoreOnlineServingConfig::class, 'Google_Service_Aiplatform_GoogleCloudAiplatformV1FeaturestoreOnlineServingConfig');
