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

namespace Google\Service\DriveActivity;

class DriveActivity extends \Google\Collection
{
  protected $collection_key = 'targets';
  protected $actionsType = Action::class;
  protected $actionsDataType = 'array';
  protected $actorsType = Actor::class;
  protected $actorsDataType = 'array';
  protected $primaryActionDetailType = ActionDetail::class;
  protected $primaryActionDetailDataType = '';
  protected $targetsType = Target::class;
  protected $targetsDataType = 'array';
  protected $timeRangeType = TimeRange::class;
  protected $timeRangeDataType = '';
  /**
   * @var string
   */
  public $timestamp;

  /**
   * @param Action[]
   */
  public function setActions($actions)
  {
    $this->actions = $actions;
  }
  /**
   * @return Action[]
   */
  public function getActions()
  {
    return $this->actions;
  }
  /**
   * @param Actor[]
   */
  public function setActors($actors)
  {
    $this->actors = $actors;
  }
  /**
   * @return Actor[]
   */
  public function getActors()
  {
    return $this->actors;
  }
  /**
   * @param ActionDetail
   */
  public function setPrimaryActionDetail(ActionDetail $primaryActionDetail)
  {
    $this->primaryActionDetail = $primaryActionDetail;
  }
  /**
   * @return ActionDetail
   */
  public function getPrimaryActionDetail()
  {
    return $this->primaryActionDetail;
  }
  /**
   * @param Target[]
   */
  public function setTargets($targets)
  {
    $this->targets = $targets;
  }
  /**
   * @return Target[]
   */
  public function getTargets()
  {
    return $this->targets;
  }
  /**
   * @param TimeRange
   */
  public function setTimeRange(TimeRange $timeRange)
  {
    $this->timeRange = $timeRange;
  }
  /**
   * @return TimeRange
   */
  public function getTimeRange()
  {
    return $this->timeRange;
  }
  /**
   * @param string
   */
  public function setTimestamp($timestamp)
  {
    $this->timestamp = $timestamp;
  }
  /**
   * @return string
   */
  public function getTimestamp()
  {
    return $this->timestamp;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DriveActivity::class, 'Google_Service_DriveActivity_DriveActivity');
