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

namespace Google\Service\CloudSearch;

class QueryInterpretationOptions extends \Google\Model
{
  /**
   * @var bool
   */
  public $disableNlInterpretation;
  /**
   * @var bool
   */
  public $disableSupplementalResults;
  /**
   * @var bool
   */
  public $enableVerbatimMode;

  /**
   * @param bool
   */
  public function setDisableNlInterpretation($disableNlInterpretation)
  {
    $this->disableNlInterpretation = $disableNlInterpretation;
  }
  /**
   * @return bool
   */
  public function getDisableNlInterpretation()
  {
    return $this->disableNlInterpretation;
  }
  /**
   * @param bool
   */
  public function setDisableSupplementalResults($disableSupplementalResults)
  {
    $this->disableSupplementalResults = $disableSupplementalResults;
  }
  /**
   * @return bool
   */
  public function getDisableSupplementalResults()
  {
    return $this->disableSupplementalResults;
  }
  /**
   * @param bool
   */
  public function setEnableVerbatimMode($enableVerbatimMode)
  {
    $this->enableVerbatimMode = $enableVerbatimMode;
  }
  /**
   * @return bool
   */
  public function getEnableVerbatimMode()
  {
    return $this->enableVerbatimMode;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(QueryInterpretationOptions::class, 'Google_Service_CloudSearch_QueryInterpretationOptions');
