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

namespace Google\Service\Dataflow;

class ApproximateProgress extends \Google\Model
{
  /**
   * @var float
   */
  public $percentComplete;
  protected $positionType = Position::class;
  protected $positionDataType = '';
  /**
   * @var string
   */
  public $remainingTime;

  /**
   * @param float
   */
  public function setPercentComplete($percentComplete)
  {
    $this->percentComplete = $percentComplete;
  }
  /**
   * @return float
   */
  public function getPercentComplete()
  {
    return $this->percentComplete;
  }
  /**
   * @param Position
   */
  public function setPosition(Position $position)
  {
    $this->position = $position;
  }
  /**
   * @return Position
   */
  public function getPosition()
  {
    return $this->position;
  }
  /**
   * @param string
   */
  public function setRemainingTime($remainingTime)
  {
    $this->remainingTime = $remainingTime;
  }
  /**
   * @return string
   */
  public function getRemainingTime()
  {
    return $this->remainingTime;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ApproximateProgress::class, 'Google_Service_Dataflow_ApproximateProgress');
