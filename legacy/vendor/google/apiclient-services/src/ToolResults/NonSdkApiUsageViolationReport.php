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

class NonSdkApiUsageViolationReport extends \Google\Collection
{
  protected $collection_key = 'exampleApis';
  protected $exampleApisType = NonSdkApi::class;
  protected $exampleApisDataType = 'array';
  /**
   * @var int
   */
  public $minSdkVersion;
  /**
   * @var int
   */
  public $targetSdkVersion;
  /**
   * @var int
   */
  public $uniqueApis;

  /**
   * @param NonSdkApi[]
   */
  public function setExampleApis($exampleApis)
  {
    $this->exampleApis = $exampleApis;
  }
  /**
   * @return NonSdkApi[]
   */
  public function getExampleApis()
  {
    return $this->exampleApis;
  }
  /**
   * @param int
   */
  public function setMinSdkVersion($minSdkVersion)
  {
    $this->minSdkVersion = $minSdkVersion;
  }
  /**
   * @return int
   */
  public function getMinSdkVersion()
  {
    return $this->minSdkVersion;
  }
  /**
   * @param int
   */
  public function setTargetSdkVersion($targetSdkVersion)
  {
    $this->targetSdkVersion = $targetSdkVersion;
  }
  /**
   * @return int
   */
  public function getTargetSdkVersion()
  {
    return $this->targetSdkVersion;
  }
  /**
   * @param int
   */
  public function setUniqueApis($uniqueApis)
  {
    $this->uniqueApis = $uniqueApis;
  }
  /**
   * @return int
   */
  public function getUniqueApis()
  {
    return $this->uniqueApis;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(NonSdkApiUsageViolationReport::class, 'Google_Service_ToolResults_NonSdkApiUsageViolationReport');
