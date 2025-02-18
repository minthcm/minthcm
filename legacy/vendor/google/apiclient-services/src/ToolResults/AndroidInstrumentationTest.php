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

class AndroidInstrumentationTest extends \Google\Collection
{
  protected $collection_key = 'testTargets';
  /**
   * @var string
   */
  public $testPackageId;
  /**
   * @var string
   */
  public $testRunnerClass;
  /**
   * @var string[]
   */
  public $testTargets;
  /**
   * @var bool
   */
  public $useOrchestrator;

  /**
   * @param string
   */
  public function setTestPackageId($testPackageId)
  {
    $this->testPackageId = $testPackageId;
  }
  /**
   * @return string
   */
  public function getTestPackageId()
  {
    return $this->testPackageId;
  }
  /**
   * @param string
   */
  public function setTestRunnerClass($testRunnerClass)
  {
    $this->testRunnerClass = $testRunnerClass;
  }
  /**
   * @return string
   */
  public function getTestRunnerClass()
  {
    return $this->testRunnerClass;
  }
  /**
   * @param string[]
   */
  public function setTestTargets($testTargets)
  {
    $this->testTargets = $testTargets;
  }
  /**
   * @return string[]
   */
  public function getTestTargets()
  {
    return $this->testTargets;
  }
  /**
   * @param bool
   */
  public function setUseOrchestrator($useOrchestrator)
  {
    $this->useOrchestrator = $useOrchestrator;
  }
  /**
   * @return bool
   */
  public function getUseOrchestrator()
  {
    return $this->useOrchestrator;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AndroidInstrumentationTest::class, 'Google_Service_ToolResults_AndroidInstrumentationTest');
