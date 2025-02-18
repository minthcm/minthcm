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

namespace Google\Service\DisplayVideo;

class BulkEditAssignedLocationsResponse extends \Google\Collection
{
  protected $collection_key = 'assignedLocations';
  protected $assignedLocationsType = AssignedLocation::class;
  protected $assignedLocationsDataType = 'array';

  /**
   * @param AssignedLocation[]
   */
  public function setAssignedLocations($assignedLocations)
  {
    $this->assignedLocations = $assignedLocations;
  }
  /**
   * @return AssignedLocation[]
   */
  public function getAssignedLocations()
  {
    return $this->assignedLocations;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(BulkEditAssignedLocationsResponse::class, 'Google_Service_DisplayVideo_BulkEditAssignedLocationsResponse');
