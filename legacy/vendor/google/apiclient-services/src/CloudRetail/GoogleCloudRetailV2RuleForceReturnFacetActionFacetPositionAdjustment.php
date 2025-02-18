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

namespace Google\Service\CloudRetail;

class GoogleCloudRetailV2RuleForceReturnFacetActionFacetPositionAdjustment extends \Google\Model
{
  /**
   * @var string
   */
  public $attributeName;
  /**
   * @var int
   */
  public $position;

  /**
   * @param string
   */
  public function setAttributeName($attributeName)
  {
    $this->attributeName = $attributeName;
  }
  /**
   * @return string
   */
  public function getAttributeName()
  {
    return $this->attributeName;
  }
  /**
   * @param int
   */
  public function setPosition($position)
  {
    $this->position = $position;
  }
  /**
   * @return int
   */
  public function getPosition()
  {
    return $this->position;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudRetailV2RuleForceReturnFacetActionFacetPositionAdjustment::class, 'Google_Service_CloudRetail_GoogleCloudRetailV2RuleForceReturnFacetActionFacetPositionAdjustment');
