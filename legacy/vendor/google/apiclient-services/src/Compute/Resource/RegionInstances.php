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

namespace Google\Service\Compute\Resource;

use Google\Service\Compute\BulkInsertInstanceResource;
use Google\Service\Compute\Operation;

/**
 * The "regionInstances" collection of methods.
 * Typical usage is:
 *  <code>
 *   $computeService = new Google\Service\Compute(...);
 *   $regionInstances = $computeService->regionInstances;
 *  </code>
 */
class RegionInstances extends \Google\Service\Resource
{
  /**
   * Creates multiple instances in a given region. Count specifies the number of
   * instances to create. (regionInstances.bulkInsert)
   *
   * @param string $project Project ID for this request.
   * @param string $region The name of the region for this request.
   * @param BulkInsertInstanceResource $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string requestId An optional request ID to identify requests.
   * Specify a unique request ID so that if you must retry your request, the
   * server will know to ignore the request if it has already been completed. For
   * example, consider a situation where you make an initial request and the
   * request times out. If you make the request again with the same request ID,
   * the server can check if original operation with the same request ID was
   * received, and if so, will ignore the second request. This prevents clients
   * from accidentally creating duplicate commitments. The request ID must be a
   * valid UUID with the exception that zero UUID is not supported (
   * 00000000-0000-0000-0000-000000000000).
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function bulkInsert($project, $region, BulkInsertInstanceResource $postBody, $optParams = [])
  {
    $params = ['project' => $project, 'region' => $region, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('bulkInsert', [$params], Operation::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(RegionInstances::class, 'Google_Service_Compute_Resource_RegionInstances');
