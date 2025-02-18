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

class GoogleCloudAiplatformV1ModelMonitoringObjectiveConfig extends \Google\Model
{
  protected $explanationConfigType = GoogleCloudAiplatformV1ModelMonitoringObjectiveConfigExplanationConfig::class;
  protected $explanationConfigDataType = '';
  protected $predictionDriftDetectionConfigType = GoogleCloudAiplatformV1ModelMonitoringObjectiveConfigPredictionDriftDetectionConfig::class;
  protected $predictionDriftDetectionConfigDataType = '';
  protected $trainingDatasetType = GoogleCloudAiplatformV1ModelMonitoringObjectiveConfigTrainingDataset::class;
  protected $trainingDatasetDataType = '';
  protected $trainingPredictionSkewDetectionConfigType = GoogleCloudAiplatformV1ModelMonitoringObjectiveConfigTrainingPredictionSkewDetectionConfig::class;
  protected $trainingPredictionSkewDetectionConfigDataType = '';

  /**
   * @param GoogleCloudAiplatformV1ModelMonitoringObjectiveConfigExplanationConfig
   */
  public function setExplanationConfig(GoogleCloudAiplatformV1ModelMonitoringObjectiveConfigExplanationConfig $explanationConfig)
  {
    $this->explanationConfig = $explanationConfig;
  }
  /**
   * @return GoogleCloudAiplatformV1ModelMonitoringObjectiveConfigExplanationConfig
   */
  public function getExplanationConfig()
  {
    return $this->explanationConfig;
  }
  /**
   * @param GoogleCloudAiplatformV1ModelMonitoringObjectiveConfigPredictionDriftDetectionConfig
   */
  public function setPredictionDriftDetectionConfig(GoogleCloudAiplatformV1ModelMonitoringObjectiveConfigPredictionDriftDetectionConfig $predictionDriftDetectionConfig)
  {
    $this->predictionDriftDetectionConfig = $predictionDriftDetectionConfig;
  }
  /**
   * @return GoogleCloudAiplatformV1ModelMonitoringObjectiveConfigPredictionDriftDetectionConfig
   */
  public function getPredictionDriftDetectionConfig()
  {
    return $this->predictionDriftDetectionConfig;
  }
  /**
   * @param GoogleCloudAiplatformV1ModelMonitoringObjectiveConfigTrainingDataset
   */
  public function setTrainingDataset(GoogleCloudAiplatformV1ModelMonitoringObjectiveConfigTrainingDataset $trainingDataset)
  {
    $this->trainingDataset = $trainingDataset;
  }
  /**
   * @return GoogleCloudAiplatformV1ModelMonitoringObjectiveConfigTrainingDataset
   */
  public function getTrainingDataset()
  {
    return $this->trainingDataset;
  }
  /**
   * @param GoogleCloudAiplatformV1ModelMonitoringObjectiveConfigTrainingPredictionSkewDetectionConfig
   */
  public function setTrainingPredictionSkewDetectionConfig(GoogleCloudAiplatformV1ModelMonitoringObjectiveConfigTrainingPredictionSkewDetectionConfig $trainingPredictionSkewDetectionConfig)
  {
    $this->trainingPredictionSkewDetectionConfig = $trainingPredictionSkewDetectionConfig;
  }
  /**
   * @return GoogleCloudAiplatformV1ModelMonitoringObjectiveConfigTrainingPredictionSkewDetectionConfig
   */
  public function getTrainingPredictionSkewDetectionConfig()
  {
    return $this->trainingPredictionSkewDetectionConfig;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAiplatformV1ModelMonitoringObjectiveConfig::class, 'Google_Service_Aiplatform_GoogleCloudAiplatformV1ModelMonitoringObjectiveConfig');
