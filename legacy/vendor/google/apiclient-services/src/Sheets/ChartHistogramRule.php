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

namespace Google\Service\Sheets;

class ChartHistogramRule extends \Google\Model
{
  public $intervalSize;
  public $maxValue;
  public $minValue;

  public function setIntervalSize($intervalSize)
  {
    $this->intervalSize = $intervalSize;
  }
  public function getIntervalSize()
  {
    return $this->intervalSize;
  }
  public function setMaxValue($maxValue)
  {
    $this->maxValue = $maxValue;
  }
  public function getMaxValue()
  {
    return $this->maxValue;
  }
  public function setMinValue($minValue)
  {
    $this->minValue = $minValue;
  }
  public function getMinValue()
  {
    return $this->minValue;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ChartHistogramRule::class, 'Google_Service_Sheets_ChartHistogramRule');
