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

namespace Google\Service\DLP;

class GooglePrivacyDlpV2TransformationLocation extends \Google\Model
{
  /**
   * @var string
   */
  public $containerType;
  /**
   * @var string
   */
  public $findingId;
  protected $recordTransformationType = GooglePrivacyDlpV2RecordTransformation::class;
  protected $recordTransformationDataType = '';

  /**
   * @param string
   */
  public function setContainerType($containerType)
  {
    $this->containerType = $containerType;
  }
  /**
   * @return string
   */
  public function getContainerType()
  {
    return $this->containerType;
  }
  /**
   * @param string
   */
  public function setFindingId($findingId)
  {
    $this->findingId = $findingId;
  }
  /**
   * @return string
   */
  public function getFindingId()
  {
    return $this->findingId;
  }
  /**
   * @param GooglePrivacyDlpV2RecordTransformation
   */
  public function setRecordTransformation(GooglePrivacyDlpV2RecordTransformation $recordTransformation)
  {
    $this->recordTransformation = $recordTransformation;
  }
  /**
   * @return GooglePrivacyDlpV2RecordTransformation
   */
  public function getRecordTransformation()
  {
    return $this->recordTransformation;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GooglePrivacyDlpV2TransformationLocation::class, 'Google_Service_DLP_GooglePrivacyDlpV2TransformationLocation');
