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

namespace Google\Service\Calendar;

class EventWorkingLocationProperties extends \Google\Model
{
  protected $customLocationType = EventWorkingLocationPropertiesCustomLocation::class;
  protected $customLocationDataType = '';
  /**
   * @var array
   */
  public $homeOffice;
  protected $officeLocationType = EventWorkingLocationPropertiesOfficeLocation::class;
  protected $officeLocationDataType = '';
  /**
   * @var string
   */
  public $type;

  /**
   * @param EventWorkingLocationPropertiesCustomLocation
   */
  public function setCustomLocation(EventWorkingLocationPropertiesCustomLocation $customLocation)
  {
    $this->customLocation = $customLocation;
  }
  /**
   * @return EventWorkingLocationPropertiesCustomLocation
   */
  public function getCustomLocation()
  {
    return $this->customLocation;
  }
  /**
   * @param array
   */
  public function setHomeOffice($homeOffice)
  {
    $this->homeOffice = $homeOffice;
  }
  /**
   * @return array
   */
  public function getHomeOffice()
  {
    return $this->homeOffice;
  }
  /**
   * @param EventWorkingLocationPropertiesOfficeLocation
   */
  public function setOfficeLocation(EventWorkingLocationPropertiesOfficeLocation $officeLocation)
  {
    $this->officeLocation = $officeLocation;
  }
  /**
   * @return EventWorkingLocationPropertiesOfficeLocation
   */
  public function getOfficeLocation()
  {
    return $this->officeLocation;
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
class_alias(EventWorkingLocationProperties::class, 'Google_Service_Calendar_EventWorkingLocationProperties');
