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

namespace Google\Service\Bigquery;

class HighCardinalityJoin extends \Google\Model
{
  /**
   * @var string
   */
  public $leftRows;
  /**
   * @var string
   */
  public $outputRows;
  /**
   * @var string
   */
  public $rightRows;
  /**
   * @var int
   */
  public $stepIndex;

  /**
   * @param string
   */
  public function setLeftRows($leftRows)
  {
    $this->leftRows = $leftRows;
  }
  /**
   * @return string
   */
  public function getLeftRows()
  {
    return $this->leftRows;
  }
  /**
   * @param string
   */
  public function setOutputRows($outputRows)
  {
    $this->outputRows = $outputRows;
  }
  /**
   * @return string
   */
  public function getOutputRows()
  {
    return $this->outputRows;
  }
  /**
   * @param string
   */
  public function setRightRows($rightRows)
  {
    $this->rightRows = $rightRows;
  }
  /**
   * @return string
   */
  public function getRightRows()
  {
    return $this->rightRows;
  }
  /**
   * @param int
   */
  public function setStepIndex($stepIndex)
  {
    $this->stepIndex = $stepIndex;
  }
  /**
   * @return int
   */
  public function getStepIndex()
  {
    return $this->stepIndex;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(HighCardinalityJoin::class, 'Google_Service_Bigquery_HighCardinalityJoin');
