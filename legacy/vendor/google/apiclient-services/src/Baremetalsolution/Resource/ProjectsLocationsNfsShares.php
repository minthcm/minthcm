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

namespace Google\Service\Baremetalsolution\Resource;

use Google\Service\Baremetalsolution\ListNfsSharesResponse;
use Google\Service\Baremetalsolution\NfsShare;
use Google\Service\Baremetalsolution\Operation;
use Google\Service\Baremetalsolution\RenameNfsShareRequest;

/**
 * The "nfsShares" collection of methods.
 * Typical usage is:
 *  <code>
 *   $baremetalsolutionService = new Google\Service\Baremetalsolution(...);
 *   $nfsShares = $baremetalsolutionService->projects_locations_nfsShares;
 *  </code>
 */
class ProjectsLocationsNfsShares extends \Google\Service\Resource
{
  /**
   * Create an NFS share. (nfsShares.create)
   *
   * @param string $parent Required. The parent project and location.
   * @param NfsShare $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function create($parent, NfsShare $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], Operation::class);
  }
  /**
   * Delete an NFS share. The underlying volume is automatically deleted.
   * (nfsShares.delete)
   *
   * @param string $name Required. The name of the NFS share to delete.
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], Operation::class);
  }
  /**
   * Get details of a single NFS share. (nfsShares.get)
   *
   * @param string $name Required. Name of the resource.
   * @param array $optParams Optional parameters.
   * @return NfsShare
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], NfsShare::class);
  }
  /**
   * List NFS shares. (nfsShares.listProjectsLocationsNfsShares)
   *
   * @param string $parent Required. Parent value for ListNfsSharesRequest.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter List filter.
   * @opt_param int pageSize Requested page size. The server might return fewer
   * items than requested. If unspecified, server will pick an appropriate
   * default.
   * @opt_param string pageToken A token identifying a page of results from the
   * server.
   * @return ListNfsSharesResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsLocationsNfsShares($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListNfsSharesResponse::class);
  }
  /**
   * Update details of a single NFS share. (nfsShares.patch)
   *
   * @param string $name Immutable. The name of the NFS share.
   * @param NfsShare $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string updateMask The list of fields to update. The only currently
   * supported fields are: `labels` `allowed_clients`
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function patch($name, NfsShare $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], Operation::class);
  }
  /**
   * RenameNfsShare sets a new name for an nfsshare. Use with caution, previous
   * names become immediately invalidated. (nfsShares.rename)
   *
   * @param string $name Required. The `name` field is used to identify the
   * nfsshare. Format:
   * projects/{project}/locations/{location}/nfsshares/{nfsshare}
   * @param RenameNfsShareRequest $postBody
   * @param array $optParams Optional parameters.
   * @return NfsShare
   * @throws \Google\Service\Exception
   */
  public function rename($name, RenameNfsShareRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('rename', [$params], NfsShare::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsNfsShares::class, 'Google_Service_Baremetalsolution_Resource_ProjectsLocationsNfsShares');
