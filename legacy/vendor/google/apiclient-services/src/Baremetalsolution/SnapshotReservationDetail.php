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

namespace Google\Service\Baremetalsolution;

class SnapshotReservationDetail extends \Google\Model
{
  /**
   * @var string
   */
  public $reservedSpaceGib;
  /**
   * @var int
   */
  public $reservedSpacePercent;
  /**
   * @var string
   */
  public $reservedSpaceRemainingGib;
  /**
   * @var int
   */
  public $reservedSpaceUsedPercent;

  /**
   * @param string
   */
  public function setReservedSpaceGib($reservedSpaceGib)
  {
    $this->reservedSpaceGib = $reservedSpaceGib;
  }
  /**
   * @return string
   */
  public function getReservedSpaceGib()
  {
    return $this->reservedSpaceGib;
  }
  /**
   * @param int
   */
  public function setReservedSpacePercent($reservedSpacePercent)
  {
    $this->reservedSpacePercent = $reservedSpacePercent;
  }
  /**
   * @return int
   */
  public function getReservedSpacePercent()
  {
    return $this->reservedSpacePercent;
  }
  /**
   * @param string
   */
  public function setReservedSpaceRemainingGib($reservedSpaceRemainingGib)
  {
    $this->reservedSpaceRemainingGib = $reservedSpaceRemainingGib;
  }
  /**
   * @return string
   */
  public function getReservedSpaceRemainingGib()
  {
    return $this->reservedSpaceRemainingGib;
  }
  /**
   * @param int
   */
  public function setReservedSpaceUsedPercent($reservedSpaceUsedPercent)
  {
    $this->reservedSpaceUsedPercent = $reservedSpaceUsedPercent;
  }
  /**
   * @return int
   */
  public function getReservedSpaceUsedPercent()
  {
    return $this->reservedSpaceUsedPercent;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SnapshotReservationDetail::class, 'Google_Service_Baremetalsolution_SnapshotReservationDetail');
