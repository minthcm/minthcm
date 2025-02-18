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

namespace Google\Service\ToolResults\Resource;

use Google\Service\ToolResults\Execution;
use Google\Service\ToolResults\ListExecutionsResponse;

/**
 * The "executions" collection of methods.
 * Typical usage is:
 *  <code>
 *   $toolresultsService = new Google\Service\ToolResults(...);
 *   $executions = $toolresultsService->projects_histories_executions;
 *  </code>
 */
class ProjectsHistoriesExecutions extends \Google\Service\Resource
{
  /**
   * Creates an Execution. The returned Execution will have the id set. May return
   * any of the following canonical error codes: - PERMISSION_DENIED - if the user
   * is not authorized to write to project - INVALID_ARGUMENT - if the request is
   * malformed - NOT_FOUND - if the containing History does not exist
   * (executions.create)
   *
   * @param string $projectId A Project id. Required.
   * @param string $historyId A History id. Required.
   * @param Execution $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string requestId A unique request ID for server to detect
   * duplicated requests. For example, a UUID. Optional, but strongly recommended.
   * @return Execution
   * @throws \Google\Service\Exception
   */
  public function create($projectId, $historyId, Execution $postBody, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'historyId' => $historyId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], Execution::class);
  }
  /**
   * Gets an Execution. May return any of the following canonical error codes: -
   * PERMISSION_DENIED - if the user is not authorized to write to project -
   * INVALID_ARGUMENT - if the request is malformed - NOT_FOUND - if the Execution
   * does not exist (executions.get)
   *
   * @param string $projectId A Project id. Required.
   * @param string $historyId A History id. Required.
   * @param string $executionId An Execution id. Required.
   * @param array $optParams Optional parameters.
   * @return Execution
   * @throws \Google\Service\Exception
   */
  public function get($projectId, $historyId, $executionId, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'historyId' => $historyId, 'executionId' => $executionId];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Execution::class);
  }
  /**
   * Lists Executions for a given History. The executions are sorted by
   * creation_time in descending order. The execution_id key will be used to order
   * the executions with the same creation_time. May return any of the following
   * canonical error codes: - PERMISSION_DENIED - if the user is not authorized to
   * read project - INVALID_ARGUMENT - if the request is malformed - NOT_FOUND -
   * if the containing History does not exist
   * (executions.listProjectsHistoriesExecutions)
   *
   * @param string $projectId A Project id. Required.
   * @param string $historyId A History id. Required.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int pageSize The maximum number of Executions to fetch. Default
   * value: 25. The server will use this default if the field is not set or has a
   * value of 0. Optional.
   * @opt_param string pageToken A continuation token to resume the query at the
   * next item. Optional.
   * @return ListExecutionsResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsHistoriesExecutions($projectId, $historyId, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'historyId' => $historyId];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListExecutionsResponse::class);
  }
  /**
   * Updates an existing Execution with the supplied partial entity. May return
   * any of the following canonical error codes: - PERMISSION_DENIED - if the user
   * is not authorized to write to project - INVALID_ARGUMENT - if the request is
   * malformed - FAILED_PRECONDITION - if the requested state transition is
   * illegal - NOT_FOUND - if the containing History does not exist
   * (executions.patch)
   *
   * @param string $projectId A Project id. Required.
   * @param string $historyId Required.
   * @param string $executionId Required.
   * @param Execution $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string requestId A unique request ID for server to detect
   * duplicated requests. For example, a UUID. Optional, but strongly recommended.
   * @return Execution
   * @throws \Google\Service\Exception
   */
  public function patch($projectId, $historyId, $executionId, Execution $postBody, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'historyId' => $historyId, 'executionId' => $executionId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], Execution::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsHistoriesExecutions::class, 'Google_Service_ToolResults_Resource_ProjectsHistoriesExecutions');
