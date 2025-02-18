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

class TestIssue extends \Google\Model
{
  /**
   * @var string
   */
  public $category;
  /**
   * @var string
   */
  public $errorMessage;
  /**
   * @var string
   */
  public $severity;
  protected $stackTraceType = StackTrace::class;
  protected $stackTraceDataType = '';
  /**
   * @var string
   */
  public $type;
  protected $warningType = Any::class;
  protected $warningDataType = '';

  /**
   * @param string
   */
  public function setCategory($category)
  {
    $this->category = $category;
  }
  /**
   * @return string
   */
  public function getCategory()
  {
    return $this->category;
  }
  /**
   * @param string
   */
  public function setErrorMessage($errorMessage)
  {
    $this->errorMessage = $errorMessage;
  }
  /**
   * @return string
   */
  public function getErrorMessage()
  {
    return $this->errorMessage;
  }
  /**
   * @param string
   */
  public function setSeverity($severity)
  {
    $this->severity = $severity;
  }
  /**
   * @return string
   */
  public function getSeverity()
  {
    return $this->severity;
  }
  /**
   * @param StackTrace
   */
  public function setStackTrace(StackTrace $stackTrace)
  {
    $this->stackTrace = $stackTrace;
  }
  /**
   * @return StackTrace
   */
  public function getStackTrace()
  {
    return $this->stackTrace;
  }
  /**
   * @param string
   */
  public function setType($type)
  {
    $this->type = $type;
  }
  /**
   * @return string
   */
  public function getType()
  {
    return $this->type;
  }
  /**
   * @param Any
   */
  public function setWarning(Any $warning)
  {
    $this->warning = $warning;
  }
  /**
   * @return Any
   */
  public function getWarning()
  {
    return $this->warning;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(TestIssue::class, 'Google_Service_ToolResults_TestIssue');
