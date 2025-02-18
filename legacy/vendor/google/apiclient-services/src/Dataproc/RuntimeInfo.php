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

namespace Google\Service\Dataproc;

class RuntimeInfo extends \Google\Model
{
  protected $approximateUsageType = UsageMetrics::class;
  protected $approximateUsageDataType = '';
  protected $currentUsageType = UsageSnapshot::class;
  protected $currentUsageDataType = '';
  /**
   * @var string
   */
  public $diagnosticOutputUri;
  /**
   * @var string[]
   */
  public $endpoints;
  /**
   * @var string
   */
  public $outputUri;

  /**
   * @param UsageMetrics
   */
  public function setApproximateUsage(UsageMetrics $approximateUsage)
  {
    $this->approximateUsage = $approximateUsage;
  }
  /**
   * @return UsageMetrics
   */
  public function getApproximateUsage()
  {
    return $this->approximateUsage;
  }
  /**
   * @param UsageSnapshot
   */
  public function setCurrentUsage(UsageSnapshot $currentUsage)
  {
    $this->currentUsage = $currentUsage;
  }
  /**
   * @return UsageSnapshot
   */
  public function getCurrentUsage()
  {
    return $this->currentUsage;
  }
  /**
   * @param string
   */
  public function setDiagnosticOutputUri($diagnosticOutputUri)
  {
    $this->diagnosticOutputUri = $diagnosticOutputUri;
  }
  /**
   * @return string
   */
  public function getDiagnosticOutputUri()
  {
    return $this->diagnosticOutputUri;
  }
  /**
   * @param string[]
   */
  public function setEndpoints($endpoints)
  {
    $this->endpoints = $endpoints;
  }
  /**
   * @return string[]
   */
  public function getEndpoints()
  {
    return $this->endpoints;
  }
  /**
   * @param string
   */
  public function setOutputUri($outputUri)
  {
    $this->outputUri = $outputUri;
  }
  /**
   * @return string
   */
  public function getOutputUri()
  {
    return $this->outputUri;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(RuntimeInfo::class, 'Google_Service_Dataproc_RuntimeInfo');
