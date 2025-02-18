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

class CloudAiLargeModelsVisionSemanticFilterResponse extends \Google\Collection
{
  protected $collection_key = 'namedBoundingBoxes';
  protected $namedBoundingBoxesType = CloudAiLargeModelsVisionNamedBoundingBox::class;
  protected $namedBoundingBoxesDataType = 'array';
  /**
   * @var bool
   */
  public $passedSemanticFilter;

  /**
   * @param CloudAiLargeModelsVisionNamedBoundingBox[]
   */
  public function setNamedBoundingBoxes($namedBoundingBoxes)
  {
    $this->namedBoundingBoxes = $namedBoundingBoxes;
  }
  /**
   * @return CloudAiLargeModelsVisionNamedBoundingBox[]
   */
  public function getNamedBoundingBoxes()
  {
    return $this->namedBoundingBoxes;
  }
  /**
   * @param bool
   */
  public function setPassedSemanticFilter($passedSemanticFilter)
  {
    $this->passedSemanticFilter = $passedSemanticFilter;
  }
  /**
   * @return bool
   */
  public function getPassedSemanticFilter()
  {
    return $this->passedSemanticFilter;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CloudAiLargeModelsVisionSemanticFilterResponse::class, 'Google_Service_Aiplatform_CloudAiLargeModelsVisionSemanticFilterResponse');
