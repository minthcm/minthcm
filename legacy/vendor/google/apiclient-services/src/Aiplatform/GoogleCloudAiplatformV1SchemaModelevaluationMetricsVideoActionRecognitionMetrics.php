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

class GoogleCloudAiplatformV1SchemaModelevaluationMetricsVideoActionRecognitionMetrics extends \Google\Collection
{
  protected $collection_key = 'videoActionMetrics';
  /**
   * @var int
   */
  public $evaluatedActionCount;
  protected $videoActionMetricsType = GoogleCloudAiplatformV1SchemaModelevaluationMetricsVideoActionMetrics::class;
  protected $videoActionMetricsDataType = 'array';

  /**
   * @param int
   */
  public function setEvaluatedActionCount($evaluatedActionCount)
  {
    $this->evaluatedActionCount = $evaluatedActionCount;
  }
  /**
   * @return int
   */
  public function getEvaluatedActionCount()
  {
    return $this->evaluatedActionCount;
  }
  /**
   * @param GoogleCloudAiplatformV1SchemaModelevaluationMetricsVideoActionMetrics[]
   */
  public function setVideoActionMetrics($videoActionMetrics)
  {
    $this->videoActionMetrics = $videoActionMetrics;
  }
  /**
   * @return GoogleCloudAiplatformV1SchemaModelevaluationMetricsVideoActionMetrics[]
   */
  public function getVideoActionMetrics()
  {
    return $this->videoActionMetrics;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAiplatformV1SchemaModelevaluationMetricsVideoActionRecognitionMetrics::class, 'Google_Service_Aiplatform_GoogleCloudAiplatformV1SchemaModelevaluationMetricsVideoActionRecognitionMetrics');
