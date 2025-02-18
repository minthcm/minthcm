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

namespace Google\Service\Compute;

class LocationPolicy extends \Google\Model
{
  protected $locationsType = LocationPolicyLocation::class;
  protected $locationsDataType = 'map';
  /**
   * @var string
   */
  public $targetShape;

  /**
   * @param LocationPolicyLocation[]
   */
  public function setLocations($locations)
  {
    $this->locations = $locations;
  }
  /**
   * @return LocationPolicyLocation[]
   */
  public function getLocations()
  {
    return $this->locations;
  }
  /**
   * @param string
   */
  public function setTargetShape($targetShape)
  {
    $this->targetShape = $targetShape;
  }
  /**
   * @return string
   */
  public function getTargetShape()
  {
    return $this->targetShape;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(LocationPolicy::class, 'Google_Service_Compute_LocationPolicy');
