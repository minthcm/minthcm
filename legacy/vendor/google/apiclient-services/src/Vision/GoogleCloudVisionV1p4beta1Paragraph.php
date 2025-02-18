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

namespace Google\Service\Vision;

class GoogleCloudVisionV1p4beta1Paragraph extends \Google\Collection
{
  protected $collection_key = 'words';
  protected $boundingBoxType = GoogleCloudVisionV1p4beta1BoundingPoly::class;
  protected $boundingBoxDataType = '';
  /**
   * @var float
   */
  public $confidence;
  protected $propertyType = GoogleCloudVisionV1p4beta1TextAnnotationTextProperty::class;
  protected $propertyDataType = '';
  protected $wordsType = GoogleCloudVisionV1p4beta1Word::class;
  protected $wordsDataType = 'array';

  /**
   * @param GoogleCloudVisionV1p4beta1BoundingPoly
   */
  public function setBoundingBox(GoogleCloudVisionV1p4beta1BoundingPoly $boundingBox)
  {
    $this->boundingBox = $boundingBox;
  }
  /**
   * @return GoogleCloudVisionV1p4beta1BoundingPoly
   */
  public function getBoundingBox()
  {
    return $this->boundingBox;
  }
  /**
   * @param float
   */
  public function setConfidence($confidence)
  {
    $this->confidence = $confidence;
  }
  /**
   * @return float
   */
  public function getConfidence()
  {
    return $this->confidence;
  }
  /**
   * @param GoogleCloudVisionV1p4beta1TextAnnotationTextProperty
   */
  public function setProperty(GoogleCloudVisionV1p4beta1TextAnnotationTextProperty $property)
  {
    $this->property = $property;
  }
  /**
   * @return GoogleCloudVisionV1p4beta1TextAnnotationTextProperty
   */
  public function getProperty()
  {
    return $this->property;
  }
  /**
   * @param GoogleCloudVisionV1p4beta1Word[]
   */
  public function setWords($words)
  {
    $this->words = $words;
  }
  /**
   * @return GoogleCloudVisionV1p4beta1Word[]
   */
  public function getWords()
  {
    return $this->words;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudVisionV1p4beta1Paragraph::class, 'Google_Service_Vision_GoogleCloudVisionV1p4beta1Paragraph');
