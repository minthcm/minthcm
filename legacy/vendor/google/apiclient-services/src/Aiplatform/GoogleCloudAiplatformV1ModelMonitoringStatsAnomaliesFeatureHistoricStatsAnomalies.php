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

class GoogleCloudAiplatformV1ModelMonitoringStatsAnomaliesFeatureHistoricStatsAnomalies extends \Google\Collection
{
  protected $collection_key = 'predictionStats';
  /**
   * @var string
   */
  public $featureDisplayName;
  protected $predictionStatsType = GoogleCloudAiplatformV1FeatureStatsAnomaly::class;
  protected $predictionStatsDataType = 'array';
  protected $thresholdType = GoogleCloudAiplatformV1ThresholdConfig::class;
  protected $thresholdDataType = '';
  protected $trainingStatsType = GoogleCloudAiplatformV1FeatureStatsAnomaly::class;
  protected $trainingStatsDataType = '';

  /**
   * @param string
   */
  public function setFeatureDisplayName($featureDisplayName)
  {
    $this->featureDisplayName = $featureDisplayName;
  }
  /**
   * @return string
   */
  public function getFeatureDisplayName()
  {
    return $this->featureDisplayName;
  }
  /**
   * @param GoogleCloudAiplatformV1FeatureStatsAnomaly[]
   */
  public function setPredictionStats($predictionStats)
  {
    $this->predictionStats = $predictionStats;
  }
  /**
   * @return GoogleCloudAiplatformV1FeatureStatsAnomaly[]
   */
  public function getPredictionStats()
  {
    return $this->predictionStats;
  }
  /**
   * @param GoogleCloudAiplatformV1ThresholdConfig
   */
  public function setThreshold(GoogleCloudAiplatformV1ThresholdConfig $threshold)
  {
    $this->threshold = $threshold;
  }
  /**
   * @return GoogleCloudAiplatformV1ThresholdConfig
   */
  public function getThreshold()
  {
    return $this->threshold;
  }
  /**
   * @param GoogleCloudAiplatformV1FeatureStatsAnomaly
   */
  public function setTrainingStats(GoogleCloudAiplatformV1FeatureStatsAnomaly $trainingStats)
  {
    $this->trainingStats = $trainingStats;
  }
  /**
   * @return GoogleCloudAiplatformV1FeatureStatsAnomaly
   */
  public function getTrainingStats()
  {
    return $this->trainingStats;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAiplatformV1ModelMonitoringStatsAnomaliesFeatureHistoricStatsAnomalies::class, 'Google_Service_Aiplatform_GoogleCloudAiplatformV1ModelMonitoringStatsAnomaliesFeatureHistoricStatsAnomalies');
