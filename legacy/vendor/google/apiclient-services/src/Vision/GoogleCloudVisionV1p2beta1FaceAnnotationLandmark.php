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

class GoogleCloudVisionV1p2beta1FaceAnnotationLandmark extends \Google\Model
{
  protected $positionType = GoogleCloudVisionV1p2beta1Position::class;
  protected $positionDataType = '';
  /**
   * @var string
   */
  public $type;

  /**
   * @param GoogleCloudVisionV1p2beta1Position
   */
  public function setPosition(GoogleCloudVisionV1p2beta1Position $position)
  {
    $this->position = $position;
  }
  /**
   * @return GoogleCloudVisionV1p2beta1Position
   */
  public function getPosition()
  {
    return $this->position;
  }
  /**
   * @param string
   */
  public function setType($type)
  {
    $this->type = $type;
  }
  /**
   * @return string
   */
  public function getType()
  {
    return $this->type;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudVisionV1p2beta1FaceAnnotationLandmark::class, 'Google_Service_Vision_GoogleCloudVisionV1p2beta1FaceAnnotationLandmark');
