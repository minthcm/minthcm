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

namespace Google\Service\Dns\Resource;

use Google\Service\Dns\Change;
use Google\Service\Dns\ChangesListResponse;

/**
 * The "changes" collection of methods.
 * Typical usage is:
 *  <code>
 *   $dnsService = new Google\Service\Dns(...);
 *   $changes = $dnsService->changes;
 *  </code>
 */
class Changes extends \Google\Service\Resource
{
  /**
   * Atomically updates the ResourceRecordSet collection. (changes.create)
   *
   * @param string $project Identifies the project addressed by this request.
   * @param string $managedZone Identifies the managed zone addressed by this
   * request. Can be the managed zone name or ID.
   * @param Change $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string clientOperationId For mutating operation requests only. An
   * optional identifier specified by the client. Must be unique for operation
   * resources in the Operations collection.
   * @return Change
   * @throws \Google\Service\Exception
   */
  public function create($project, $managedZone, Change $postBody, $optParams = [])
  {
    $params = ['project' => $project, 'managedZone' => $managedZone, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], Change::class);
  }
  /**
   * Fetches the representation of an existing Change. (changes.get)
   *
   * @param string $project Identifies the project addressed by this request.
   * @param string $managedZone Identifies the managed zone addressed by this
   * request. Can be the managed zone name or ID.
   * @param string $changeId The identifier of the requested change, from a
   * previous ResourceRecordSetsChangeResponse.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string clientOperationId For mutating operation requests only. An
   * optional identifier specified by the client. Must be unique for operation
   * resources in the Operations collection.
   * @return Change
   * @throws \Google\Service\Exception
   */
  public function get($project, $managedZone, $changeId, $optParams = [])
  {
    $params = ['project' => $project, 'managedZone' => $managedZone, 'changeId' => $changeId];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Change::class);
  }
  /**
   * Enumerates Changes to a ResourceRecordSet collection. (changes.listChanges)
   *
   * @param string $project Identifies the project addressed by this request.
   * @param string $managedZone Identifies the managed zone addressed by this
   * request. Can be the managed zone name or ID.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int maxResults Optional. Maximum number of results to be returned.
   * If unspecified, the server decides how many results to return.
   * @opt_param string pageToken Optional. A tag returned by a previous list
   * request that was truncated. Use this parameter to continue a previous list
   * request.
   * @opt_param string sortBy Sorting criterion. The only supported value is
   * change sequence.
   * @opt_param string sortOrder Sorting order direction: 'ascending' or
   * 'descending'.
   * @return ChangesListResponse
   * @throws \Google\Service\Exception
   */
  public function listChanges($project, $managedZone, $optParams = [])
  {
    $params = ['project' => $project, 'managedZone' => $managedZone];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ChangesListResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Changes::class, 'Google_Service_Dns_Resource_Changes');
