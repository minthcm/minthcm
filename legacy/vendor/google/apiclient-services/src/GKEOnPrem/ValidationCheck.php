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

namespace Google\Service\GKEOnPrem;

class ValidationCheck extends \Google\Model
{
  /**
   * @var string
   */
  public $option;
  /**
   * @var string
   */
  public $scenario;
  protected $statusType = ValidationCheckStatus::class;
  protected $statusDataType = '';

  /**
   * @param string
   */
  public function setOption($option)
  {
    $this->option = $option;
  }
  /**
   * @return string
   */
  public function getOption()
  {
    return $this->option;
  }
  /**
   * @param string
   */
  public function setScenario($scenario)
  {
    $this->scenario = $scenario;
  }
  /**
   * @return string
   */
  public function getScenario()
  {
    return $this->scenario;
  }
  /**
   * @param ValidationCheckStatus
   */
  public function setStatus(ValidationCheckStatus $status)
  {
    $this->status = $status;
  }
  /**
   * @return ValidationCheckStatus
   */
  public function getStatus()
  {
    return $this->status;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ValidationCheck::class, 'Google_Service_GKEOnPrem_ValidationCheck');
