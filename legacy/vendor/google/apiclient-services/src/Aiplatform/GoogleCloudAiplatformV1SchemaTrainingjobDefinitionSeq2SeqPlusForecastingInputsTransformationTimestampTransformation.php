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

class GoogleCloudAiplatformV1SchemaTrainingjobDefinitionSeq2SeqPlusForecastingInputsTransformationTimestampTransformation extends \Google\Model
{
  /**
   * @var string
   */
  public $columnName;
  /**
   * @var string
   */
  public $timeFormat;

  /**
   * @param string
   */
  public function setColumnName($columnName)
  {
    $this->columnName = $columnName;
  }
  /**
   * @return string
   */
  public function getColumnName()
  {
    return $this->columnName;
  }
  /**
   * @param string
   */
  public function setTimeFormat($timeFormat)
  {
    $this->timeFormat = $timeFormat;
  }
  /**
   * @return string
   */
  public function getTimeFormat()
  {
    return $this->timeFormat;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAiplatformV1SchemaTrainingjobDefinitionSeq2SeqPlusForecastingInputsTransformationTimestampTransformation::class, 'Google_Service_Aiplatform_GoogleCloudAiplatformV1SchemaTrainingjobDefinitionSeq2SeqPlusForecastingInputsTransformationTimestampTransformation');
