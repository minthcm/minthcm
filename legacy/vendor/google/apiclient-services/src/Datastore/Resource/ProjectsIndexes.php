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

namespace Google\Service\Datastore\Resource;

use Google\Service\Datastore\GoogleDatastoreAdminV1Index;
use Google\Service\Datastore\GoogleDatastoreAdminV1ListIndexesResponse;
use Google\Service\Datastore\GoogleLongrunningOperation;

/**
 * The "indexes" collection of methods.
 * Typical usage is:
 *  <code>
 *   $datastoreService = new Google\Service\Datastore(...);
 *   $indexes = $datastoreService->projects_indexes;
 *  </code>
 */
class ProjectsIndexes extends \Google\Service\Resource
{
  /**
   * Creates the specified index. A newly created index's initial state is
   * `CREATING`. On completion of the returned google.longrunning.Operation, the
   * state will be `READY`. If the index already exists, the call will return an
   * `ALREADY_EXISTS` status. During index creation, the process could result in
   * an error, in which case the index will move to the `ERROR` state. The process
   * can be recovered by fixing the data that caused the error, removing the index
   * with delete, then re-creating the index with create. Indexes with a single
   * property cannot be created. (indexes.create)
   *
   * @param string $projectId Project ID against which to make the request.
   * @param GoogleDatastoreAdminV1Index $postBody
   * @param array $optParams Optional parameters.
   * @return GoogleLongrunningOperation
   * @throws \Google\Service\Exception
   */
  public function create($projectId, GoogleDatastoreAdminV1Index $postBody, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], GoogleLongrunningOperation::class);
  }
  /**
   * Deletes an existing index. An index can only be deleted if it is in a `READY`
   * or `ERROR` state. On successful execution of the request, the index will be
   * in a `DELETING` state. And on completion of the returned
   * google.longrunning.Operation, the index will be removed. During index
   * deletion, the process could result in an error, in which case the index will
   * move to the `ERROR` state. The process can be recovered by fixing the data
   * that caused the error, followed by calling delete again. (indexes.delete)
   *
   * @param string $projectId Project ID against which to make the request.
   * @param string $indexId The resource ID of the index to delete.
   * @param array $optParams Optional parameters.
   * @return GoogleLongrunningOperation
   * @throws \Google\Service\Exception
   */
  public function delete($projectId, $indexId, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'indexId' => $indexId];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], GoogleLongrunningOperation::class);
  }
  /**
   * Gets an index. (indexes.get)
   *
   * @param string $projectId Project ID against which to make the request.
   * @param string $indexId The resource ID of the index to get.
   * @param array $optParams Optional parameters.
   * @return GoogleDatastoreAdminV1Index
   * @throws \Google\Service\Exception
   */
  public function get($projectId, $indexId, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'indexId' => $indexId];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], GoogleDatastoreAdminV1Index::class);
  }
  /**
   * Lists the indexes that match the specified filters. Datastore uses an
   * eventually consistent query to fetch the list of indexes and may occasionally
   * return stale results. (indexes.listProjectsIndexes)
   *
   * @param string $projectId Project ID against which to make the request.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter
   * @opt_param int pageSize The maximum number of items to return. If zero, then
   * all results will be returned.
   * @opt_param string pageToken The next_page_token value returned from a
   * previous List request, if any.
   * @return GoogleDatastoreAdminV1ListIndexesResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsIndexes($projectId, $optParams = [])
  {
    $params = ['projectId' => $projectId];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], GoogleDatastoreAdminV1ListIndexesResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsIndexes::class, 'Google_Service_Datastore_Resource_ProjectsIndexes');
