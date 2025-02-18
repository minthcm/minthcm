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

namespace Google\Service\SemanticTile;

class BasemapZOrder extends \Google\Model
{
  public $zGrade;
  public $zPlane;
  public $zWithinGrade;

  public function setZGrade($zGrade)
  {
    $this->zGrade = $zGrade;
  }
  public function getZGrade()
  {
    return $this->zGrade;
  }
  public function setZPlane($zPlane)
  {
    $this->zPlane = $zPlane;
  }
  public function getZPlane()
  {
    return $this->zPlane;
  }
  public function setZWithinGrade($zWithinGrade)
  {
    $this->zWithinGrade = $zWithinGrade;
  }
  public function getZWithinGrade()
  {
    return $this->zWithinGrade;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(BasemapZOrder::class, 'Google_Service_SemanticTile_BasemapZOrder');
