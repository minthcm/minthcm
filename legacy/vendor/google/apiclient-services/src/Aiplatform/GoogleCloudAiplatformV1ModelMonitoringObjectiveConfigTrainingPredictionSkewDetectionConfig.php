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

class GoogleCloudAiplatformV1ModelMonitoringObjectiveConfigTrainingPredictionSkewDetectionConfig extends \Google\Model
{
  protected $attributionScoreSkewThresholdsType = GoogleCloudAiplatformV1ThresholdConfig::class;
  protected $attributionScoreSkewThresholdsDataType = 'map';
  protected $defaultSkewThresholdType = GoogleCloudAiplatformV1ThresholdConfig::class;
  protected $defaultSkewThresholdDataType = '';
  protected $skewThresholdsType = GoogleCloudAiplatformV1ThresholdConfig::class;
  protected $skewThresholdsDataType = 'map';

  /**
   * @param GoogleCloudAiplatformV1ThresholdConfig[]
   */
  public function setAttributionScoreSkewThresholds($attributionScoreSkewThresholds)
  {
    $this->attributionScoreSkewThresholds = $attributionScoreSkewThresholds;
  }
  /**
   * @return GoogleCloudAiplatformV1ThresholdConfig[]
   */
  public function getAttributionScoreSkewThresholds()
  {
    return $this->attributionScoreSkewThresholds;
  }
  /**
   * @param GoogleCloudAiplatformV1ThresholdConfig
   */
  public function setDefaultSkewThreshold(GoogleCloudAiplatformV1ThresholdConfig $defaultSkewThreshold)
  {
    $this->defaultSkewThreshold = $defaultSkewThreshold;
  }
  /**
   * @return GoogleCloudAiplatformV1ThresholdConfig
   */
  public function getDefaultSkewThreshold()
  {
    return $this->defaultSkewThreshold;
  }
  /**
   * @param GoogleCloudAiplatformV1ThresholdConfig[]
   */
  public function setSkewThresholds($skewThresholds)
  {
    $this->skewThresholds = $skewThresholds;
  }
  /**
   * @return GoogleCloudAiplatformV1ThresholdConfig[]
   */
  public function getSkewThresholds()
  {
    return $this->skewThresholds;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAiplatformV1ModelMonitoringObjectiveConfigTrainingPredictionSkewDetectionConfig::class, 'Google_Service_Aiplatform_GoogleCloudAiplatformV1ModelMonitoringObjectiveConfigTrainingPredictionSkewDetectionConfig');
