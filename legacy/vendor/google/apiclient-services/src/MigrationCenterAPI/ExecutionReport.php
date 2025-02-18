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

namespace Google\Service\MigrationCenterAPI;

class ExecutionReport extends \Google\Model
{
  protected $executionErrorsType = ValidationReport::class;
  protected $executionErrorsDataType = '';
  /**
   * @var int
   */
  public $framesReported;
  /**
   * @var int
   */
  public $totalRowsCount;

  /**
   * @param ValidationReport
   */
  public function setExecutionErrors(ValidationReport $executionErrors)
  {
    $this->executionErrors = $executionErrors;
  }
  /**
   * @return ValidationReport
   */
  public function getExecutionErrors()
  {
    return $this->executionErrors;
  }
  /**
   * @param int
   */
  public function setFramesReported($framesReported)
  {
    $this->framesReported = $framesReported;
  }
  /**
   * @return int
   */
  public function getFramesReported()
  {
    return $this->framesReported;
  }
  /**
   * @param int
   */
  public function setTotalRowsCount($totalRowsCount)
  {
    $this->totalRowsCount = $totalRowsCount;
  }
  /**
   * @return int
   */
  public function getTotalRowsCount()
  {
    return $this->totalRowsCount;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ExecutionReport::class, 'Google_Service_MigrationCenterAPI_ExecutionReport');
