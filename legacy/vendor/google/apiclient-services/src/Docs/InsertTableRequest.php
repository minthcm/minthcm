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

namespace Google\Service\Docs;

class InsertTableRequest extends \Google\Model
{
  /**
   * @var int
   */
  public $columns;
  protected $endOfSegmentLocationType = EndOfSegmentLocation::class;
  protected $endOfSegmentLocationDataType = '';
  protected $locationType = Location::class;
  protected $locationDataType = '';
  /**
   * @var int
   */
  public $rows;

  /**
   * @param int
   */
  public function setColumns($columns)
  {
    $this->columns = $columns;
  }
  /**
   * @return int
   */
  public function getColumns()
  {
    return $this->columns;
  }
  /**
   * @param EndOfSegmentLocation
   */
  public function setEndOfSegmentLocation(EndOfSegmentLocation $endOfSegmentLocation)
  {
    $this->endOfSegmentLocation = $endOfSegmentLocation;
  }
  /**
   * @return EndOfSegmentLocation
   */
  public function getEndOfSegmentLocation()
  {
    return $this->endOfSegmentLocation;
  }
  /**
   * @param Location
   */
  public function setLocation(Location $location)
  {
    $this->location = $location;
  }
  /**
   * @return Location
   */
  public function getLocation()
  {
    return $this->location;
  }
  /**
   * @param int
   */
  public function setRows($rows)
  {
    $this->rows = $rows;
  }
  /**
   * @return int
   */
  public function getRows()
  {
    return $this->rows;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(InsertTableRequest::class, 'Google_Service_Docs_InsertTableRequest');
