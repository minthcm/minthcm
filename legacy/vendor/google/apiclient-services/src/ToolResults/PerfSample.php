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

namespace Google\Service\ToolResults;

class PerfSample extends \Google\Model
{
  protected $sampleTimeType = Timestamp::class;
  protected $sampleTimeDataType = '';
  public $value;

  /**
   * @param Timestamp
   */
  public function setSampleTime(Timestamp $sampleTime)
  {
    $this->sampleTime = $sampleTime;
  }
  /**
   * @return Timestamp
   */
  public function getSampleTime()
  {
    return $this->sampleTime;
  }
  public function setValue($value)
  {
    $this->value = $value;
  }
  public function getValue()
  {
    return $this->value;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(PerfSample::class, 'Google_Service_ToolResults_PerfSample');
