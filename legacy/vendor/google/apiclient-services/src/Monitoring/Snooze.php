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

namespace Google\Service\Monitoring;

class Snooze extends \Google\Model
{
  protected $criteriaType = Criteria::class;
  protected $criteriaDataType = '';
  /**
   * @var string
   */
  public $displayName;
  protected $intervalType = TimeInterval::class;
  protected $intervalDataType = '';
  /**
   * @var string
   */
  public $name;

  /**
   * @param Criteria
   */
  public function setCriteria(Criteria $criteria)
  {
    $this->criteria = $criteria;
  }
  /**
   * @return Criteria
   */
  public function getCriteria()
  {
    return $this->criteria;
  }
  /**
   * @param string
   */
  public function setDisplayName($displayName)
  {
    $this->displayName = $displayName;
  }
  /**
   * @return string
   */
  public function getDisplayName()
  {
    return $this->displayName;
  }
  /**
   * @param TimeInterval
   */
  public function setInterval(TimeInterval $interval)
  {
    $this->interval = $interval;
  }
  /**
   * @return TimeInterval
   */
  public function getInterval()
  {
    return $this->interval;
  }
  /**
   * @param string
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Snooze::class, 'Google_Service_Monitoring_Snooze');
