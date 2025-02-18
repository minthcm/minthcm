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

namespace Google\Service\Compute;

class UrlMapValidationResult extends \Google\Collection
{
  protected $collection_key = 'testFailures';
  /**
   * @var string[]
   */
  public $loadErrors;
  /**
   * @var bool
   */
  public $loadSucceeded;
  protected $testFailuresType = TestFailure::class;
  protected $testFailuresDataType = 'array';
  /**
   * @var bool
   */
  public $testPassed;

  /**
   * @param string[]
   */
  public function setLoadErrors($loadErrors)
  {
    $this->loadErrors = $loadErrors;
  }
  /**
   * @return string[]
   */
  public function getLoadErrors()
  {
    return $this->loadErrors;
  }
  /**
   * @param bool
   */
  public function setLoadSucceeded($loadSucceeded)
  {
    $this->loadSucceeded = $loadSucceeded;
  }
  /**
   * @return bool
   */
  public function getLoadSucceeded()
  {
    return $this->loadSucceeded;
  }
  /**
   * @param TestFailure[]
   */
  public function setTestFailures($testFailures)
  {
    $this->testFailures = $testFailures;
  }
  /**
   * @return TestFailure[]
   */
  public function getTestFailures()
  {
    return $this->testFailures;
  }
  /**
   * @param bool
   */
  public function setTestPassed($testPassed)
  {
    $this->testPassed = $testPassed;
  }
  /**
   * @return bool
   */
  public function getTestPassed()
  {
    return $this->testPassed;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(UrlMapValidationResult::class, 'Google_Service_Compute_UrlMapValidationResult');
