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

namespace Google\Service\ServiceUsage;

class BatchEnableServicesResponse extends \Google\Collection
{
  protected $collection_key = 'services';
  protected $failuresType = EnableFailure::class;
  protected $failuresDataType = 'array';
  protected $servicesType = GoogleApiServiceusageV1Service::class;
  protected $servicesDataType = 'array';

  /**
   * @param EnableFailure[]
   */
  public function setFailures($failures)
  {
    $this->failures = $failures;
  }
  /**
   * @return EnableFailure[]
   */
  public function getFailures()
  {
    return $this->failures;
  }
  /**
   * @param GoogleApiServiceusageV1Service[]
   */
  public function setServices($services)
  {
    $this->services = $services;
  }
  /**
   * @return GoogleApiServiceusageV1Service[]
   */
  public function getServices()
  {
    return $this->services;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(BatchEnableServicesResponse::class, 'Google_Service_ServiceUsage_BatchEnableServicesResponse');
