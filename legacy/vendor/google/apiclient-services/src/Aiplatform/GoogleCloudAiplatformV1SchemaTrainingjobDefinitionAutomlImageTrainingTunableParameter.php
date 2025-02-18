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

class GoogleCloudAiplatformV1SchemaTrainingjobDefinitionAutomlImageTrainingTunableParameter extends \Google\Model
{
  /**
   * @var string
   */
  public $checkpointName;
  /**
   * @var string[]
   */
  public $datasetConfig;
  protected $studySpecType = GoogleCloudAiplatformV1StudySpec::class;
  protected $studySpecDataType = '';
  /**
   * @var string[]
   */
  public $trainerConfig;
  /**
   * @var string
   */
  public $trainerType;

  /**
   * @param string
   */
  public function setCheckpointName($checkpointName)
  {
    $this->checkpointName = $checkpointName;
  }
  /**
   * @return string
   */
  public function getCheckpointName()
  {
    return $this->checkpointName;
  }
  /**
   * @param string[]
   */
  public function setDatasetConfig($datasetConfig)
  {
    $this->datasetConfig = $datasetConfig;
  }
  /**
   * @return string[]
   */
  public function getDatasetConfig()
  {
    return $this->datasetConfig;
  }
  /**
   * @param GoogleCloudAiplatformV1StudySpec
   */
  public function setStudySpec(GoogleCloudAiplatformV1StudySpec $studySpec)
  {
    $this->studySpec = $studySpec;
  }
  /**
   * @return GoogleCloudAiplatformV1StudySpec
   */
  public function getStudySpec()
  {
    return $this->studySpec;
  }
  /**
   * @param string[]
   */
  public function setTrainerConfig($trainerConfig)
  {
    $this->trainerConfig = $trainerConfig;
  }
  /**
   * @return string[]
   */
  public function getTrainerConfig()
  {
    return $this->trainerConfig;
  }
  /**
   * @param string
   */
  public function setTrainerType($trainerType)
  {
    $this->trainerType = $trainerType;
  }
  /**
   * @return string
   */
  public function getTrainerType()
  {
    return $this->trainerType;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAiplatformV1SchemaTrainingjobDefinitionAutomlImageTrainingTunableParameter::class, 'Google_Service_Aiplatform_GoogleCloudAiplatformV1SchemaTrainingjobDefinitionAutomlImageTrainingTunableParameter');
