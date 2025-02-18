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

class GoogleCloudAiplatformV1ListTrainingPipelinesResponse extends \Google\Collection
{
  protected $collection_key = 'trainingPipelines';
  /**
   * @var string
   */
  public $nextPageToken;
  protected $trainingPipelinesType = GoogleCloudAiplatformV1TrainingPipeline::class;
  protected $trainingPipelinesDataType = 'array';

  /**
   * @param string
   */
  public function setNextPageToken($nextPageToken)
  {
    $this->nextPageToken = $nextPageToken;
  }
  /**
   * @return string
   */
  public function getNextPageToken()
  {
    return $this->nextPageToken;
  }
  /**
   * @param GoogleCloudAiplatformV1TrainingPipeline[]
   */
  public function setTrainingPipelines($trainingPipelines)
  {
    $this->trainingPipelines = $trainingPipelines;
  }
  /**
   * @return GoogleCloudAiplatformV1TrainingPipeline[]
   */
  public function getTrainingPipelines()
  {
    return $this->trainingPipelines;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAiplatformV1ListTrainingPipelinesResponse::class, 'Google_Service_Aiplatform_GoogleCloudAiplatformV1ListTrainingPipelinesResponse');
