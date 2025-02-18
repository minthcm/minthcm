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

namespace Google\Service\Contactcenterinsights;

class GoogleCloudContactcenterinsightsV1CreateAnalysisOperationMetadata extends \Google\Model
{
  protected $annotatorSelectorType = GoogleCloudContactcenterinsightsV1AnnotatorSelector::class;
  protected $annotatorSelectorDataType = '';
  /**
   * @var string
   */
  public $conversation;
  /**
   * @var string
   */
  public $createTime;
  /**
   * @var string
   */
  public $endTime;

  /**
   * @param GoogleCloudContactcenterinsightsV1AnnotatorSelector
   */
  public function setAnnotatorSelector(GoogleCloudContactcenterinsightsV1AnnotatorSelector $annotatorSelector)
  {
    $this->annotatorSelector = $annotatorSelector;
  }
  /**
   * @return GoogleCloudContactcenterinsightsV1AnnotatorSelector
   */
  public function getAnnotatorSelector()
  {
    return $this->annotatorSelector;
  }
  /**
   * @param string
   */
  public function setConversation($conversation)
  {
    $this->conversation = $conversation;
  }
  /**
   * @return string
   */
  public function getConversation()
  {
    return $this->conversation;
  }
  /**
   * @param string
   */
  public function setCreateTime($createTime)
  {
    $this->createTime = $createTime;
  }
  /**
   * @return string
   */
  public function getCreateTime()
  {
    return $this->createTime;
  }
  /**
   * @param string
   */
  public function setEndTime($endTime)
  {
    $this->endTime = $endTime;
  }
  /**
   * @return string
   */
  public function getEndTime()
  {
    return $this->endTime;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudContactcenterinsightsV1CreateAnalysisOperationMetadata::class, 'Google_Service_Contactcenterinsights_GoogleCloudContactcenterinsightsV1CreateAnalysisOperationMetadata');
