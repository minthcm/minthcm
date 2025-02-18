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

class GoogleCloudAiplatformV1ErrorAnalysisAnnotation extends \Google\Collection
{
  protected $collection_key = 'attributedItems';
  protected $attributedItemsType = GoogleCloudAiplatformV1ErrorAnalysisAnnotationAttributedItem::class;
  protected $attributedItemsDataType = 'array';
  public $outlierScore;
  public $outlierThreshold;
  /**
   * @var string
   */
  public $queryType;

  /**
   * @param GoogleCloudAiplatformV1ErrorAnalysisAnnotationAttributedItem[]
   */
  public function setAttributedItems($attributedItems)
  {
    $this->attributedItems = $attributedItems;
  }
  /**
   * @return GoogleCloudAiplatformV1ErrorAnalysisAnnotationAttributedItem[]
   */
  public function getAttributedItems()
  {
    return $this->attributedItems;
  }
  public function setOutlierScore($outlierScore)
  {
    $this->outlierScore = $outlierScore;
  }
  public function getOutlierScore()
  {
    return $this->outlierScore;
  }
  public function setOutlierThreshold($outlierThreshold)
  {
    $this->outlierThreshold = $outlierThreshold;
  }
  public function getOutlierThreshold()
  {
    return $this->outlierThreshold;
  }
  /**
   * @param string
   */
  public function setQueryType($queryType)
  {
    $this->queryType = $queryType;
  }
  /**
   * @return string
   */
  public function getQueryType()
  {
    return $this->queryType;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAiplatformV1ErrorAnalysisAnnotation::class, 'Google_Service_Aiplatform_GoogleCloudAiplatformV1ErrorAnalysisAnnotation');
