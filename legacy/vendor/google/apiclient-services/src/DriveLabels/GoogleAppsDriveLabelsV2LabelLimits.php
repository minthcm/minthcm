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

namespace Google\Service\DriveLabels;

class GoogleAppsDriveLabelsV2LabelLimits extends \Google\Model
{
  protected $fieldLimitsType = GoogleAppsDriveLabelsV2FieldLimits::class;
  protected $fieldLimitsDataType = '';
  /**
   * @var int
   */
  public $maxDeletedFields;
  /**
   * @var int
   */
  public $maxDescriptionLength;
  /**
   * @var int
   */
  public $maxDraftRevisions;
  /**
   * @var int
   */
  public $maxFields;
  /**
   * @var int
   */
  public $maxTitleLength;
  /**
   * @var string
   */
  public $name;

  /**
   * @param GoogleAppsDriveLabelsV2FieldLimits
   */
  public function setFieldLimits(GoogleAppsDriveLabelsV2FieldLimits $fieldLimits)
  {
    $this->fieldLimits = $fieldLimits;
  }
  /**
   * @return GoogleAppsDriveLabelsV2FieldLimits
   */
  public function getFieldLimits()
  {
    return $this->fieldLimits;
  }
  /**
   * @param int
   */
  public function setMaxDeletedFields($maxDeletedFields)
  {
    $this->maxDeletedFields = $maxDeletedFields;
  }
  /**
   * @return int
   */
  public function getMaxDeletedFields()
  {
    return $this->maxDeletedFields;
  }
  /**
   * @param int
   */
  public function setMaxDescriptionLength($maxDescriptionLength)
  {
    $this->maxDescriptionLength = $maxDescriptionLength;
  }
  /**
   * @return int
   */
  public function getMaxDescriptionLength()
  {
    return $this->maxDescriptionLength;
  }
  /**
   * @param int
   */
  public function setMaxDraftRevisions($maxDraftRevisions)
  {
    $this->maxDraftRevisions = $maxDraftRevisions;
  }
  /**
   * @return int
   */
  public function getMaxDraftRevisions()
  {
    return $this->maxDraftRevisions;
  }
  /**
   * @param int
   */
  public function setMaxFields($maxFields)
  {
    $this->maxFields = $maxFields;
  }
  /**
   * @return int
   */
  public function getMaxFields()
  {
    return $this->maxFields;
  }
  /**
   * @param int
   */
  public function setMaxTitleLength($maxTitleLength)
  {
    $this->maxTitleLength = $maxTitleLength;
  }
  /**
   * @return int
   */
  public function getMaxTitleLength()
  {
    return $this->maxTitleLength;
  }
  /**
   * @param string
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleAppsDriveLabelsV2LabelLimits::class, 'Google_Service_DriveLabels_GoogleAppsDriveLabelsV2LabelLimits');
