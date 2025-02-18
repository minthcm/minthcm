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

namespace Google\Service\Genomics\Resource;

use Google\Service\Genomics\CancelOperationRequest;
use Google\Service\Genomics\GenomicsEmpty;
use Google\Service\Genomics\ListOperationsResponse;
use Google\Service\Genomics\Operation;

/**
 * The "operations" collection of methods.
 * Typical usage is:
 *  <code>
 *   $genomicsService = new Google\Service\Genomics(...);
 *   $operations = $genomicsService->projects_operations;
 *  </code>
 */
class ProjectsOperations extends \Google\Service\Resource
{
  /**
   * Starts asynchronous cancellation on a long-running operation. The server
   * makes a best effort to cancel the operation, but success is not guaranteed.
   * Clients may use Operations.GetOperation or Operations.ListOperations to check
   * whether the cancellation succeeded or the operation completed despite
   * cancellation. Authorization requires the following [Google
   * IAM](https://cloud.google.com/iam) permission: * `genomics.operations.cancel`
   * (operations.cancel)
   *
   * @param string $name The name of the operation resource to be cancelled.
   * @param CancelOperationRequest $postBody
   * @param array $optParams Optional parameters.
   * @return GenomicsEmpty
   */
  public function cancel($name, CancelOperationRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('cancel', [$params], GenomicsEmpty::class);
  }
  /**
   * Gets the latest state of a long-running operation. Clients can use this
   * method to poll the operation result at intervals as recommended by the API
   * service. Authorization requires the following [Google
   * IAM](https://cloud.google.com/iam) permission: * `genomics.operations.get`
   * (operations.get)
   *
   * @param string $name The name of the operation resource.
   * @param array $optParams Optional parameters.
   * @return Operation
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Operation::class);
  }
  /**
   * Lists operations that match the specified filter in the request.
   * Authorization requires the following [Google
   * IAM](https://cloud.google.com/iam) permission: * `genomics.operations.list`
   * (operations.listProjectsOperations)
   *
   * @param string $name The name of the operation's parent resource.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter A string for filtering Operations. In v2alpha1, the
   * following filter fields are supported: * createTime: The time this job was
   * created * events: The set of event (names) that have occurred while running
   * the pipeline. The : operator can be used to determine if a particular event
   * has occurred. * error: If the pipeline is running, this value is NULL. Once
   * the pipeline finishes, the value is the standard Google error code. *
   * labels.key or labels."key with space" where key is a label key. * done: If
   * the pipeline is running, this value is false. Once the pipeline finishes, the
   * value is true. Examples: * `projectId = my-project AND createTime >=
   * 1432140000` * `projectId = my-project AND createTime >= 1432140000 AND
   * createTime <= 1432150000 AND status = RUNNING` * `projectId = my-project AND
   * labels.color = *` * `projectId = my-project AND labels.color = red`
   * @opt_param int pageSize The maximum number of results to return. The maximum
   * value is 256.
   * @opt_param string pageToken The standard list page token.
   * @return ListOperationsResponse
   */
  public function listProjectsOperations($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListOperationsResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsOperations::class, 'Google_Service_Genomics_Resource_ProjectsOperations');
