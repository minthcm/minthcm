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

namespace Google\Service\SQLAdmin\Resource;

use Google\Service\SQLAdmin\Operation;
use Google\Service\SQLAdmin\OperationsListResponse;
use Google\Service\SQLAdmin\SqladminEmpty;

/**
 * The "operations" collection of methods.
 * Typical usage is:
 *  <code>
 *   $sqladminService = new Google\Service\SQLAdmin(...);
 *   $operations = $sqladminService->operations;
 *  </code>
 */
class Operations extends \Google\Service\Resource
{
  /**
   * Cancels an instance operation that has been performed on an instance.
   * (operations.cancel)
   *
   * @param string $project Project ID of the project that contains the instance.
   * @param string $operation Instance operation ID.
   * @param array $optParams Optional parameters.
   * @return SqladminEmpty
   * @throws \Google\Service\Exception
   */
  public function cancel($project, $operation, $optParams = [])
  {
    $params = ['project' => $project, 'operation' => $operation];
    $params = array_merge($params, $optParams);
    return $this->call('cancel', [$params], SqladminEmpty::class);
  }
  /**
   * Retrieves an instance operation that has been performed on an instance.
   * (operations.get)
   *
   * @param string $project Project ID of the project that contains the instance.
   * @param string $operation Instance operation ID.
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function get($project, $operation, $optParams = [])
  {
    $params = ['project' => $project, 'operation' => $operation];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Operation::class);
  }
  /**
   * Lists all instance operations that have been performed on the given Cloud SQL
   * instance in the reverse chronological order of the start time.
   * (operations.listOperations)
   *
   * @param string $project Project ID of the project that contains the instance.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string instance Cloud SQL instance ID. This does not include the
   * project ID.
   * @opt_param string maxResults Maximum number of operations per response.
   * @opt_param string pageToken A previously-returned page token representing
   * part of the larger set of results to view.
   * @return OperationsListResponse
   * @throws \Google\Service\Exception
   */
  public function listOperations($project, $optParams = [])
  {
    $params = ['project' => $project];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], OperationsListResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Operations::class, 'Google_Service_SQLAdmin_Resource_Operations');
