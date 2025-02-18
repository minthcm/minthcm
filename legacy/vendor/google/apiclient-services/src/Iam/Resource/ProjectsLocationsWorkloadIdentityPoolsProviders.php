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

namespace Google\Service\Iam\Resource;

use Google\Service\Iam\ListWorkloadIdentityPoolProvidersResponse;
use Google\Service\Iam\Operation;
use Google\Service\Iam\UndeleteWorkloadIdentityPoolProviderRequest;
use Google\Service\Iam\WorkloadIdentityPoolProvider;

/**
 * The "providers" collection of methods.
 * Typical usage is:
 *  <code>
 *   $iamService = new Google\Service\Iam(...);
 *   $providers = $iamService->projects_locations_workloadIdentityPools_providers;
 *  </code>
 */
class ProjectsLocationsWorkloadIdentityPoolsProviders extends \Google\Service\Resource
{
  /**
   * Creates a new WorkloadIdentityPoolProvider in a WorkloadIdentityPool. You
   * cannot reuse the name of a deleted provider until 30 days after deletion.
   * (providers.create)
   *
   * @param string $parent Required. The pool to create this provider in.
   * @param WorkloadIdentityPoolProvider $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string workloadIdentityPoolProviderId Required. The ID for the
   * provider, which becomes the final component of the resource name. This value
   * must be 4-32 characters, and may contain the characters [a-z0-9-]. The prefix
   * `gcp-` is reserved for use by Google, and may not be specified.
   * @return Operation
   */
  public function create($parent, WorkloadIdentityPoolProvider $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], Operation::class);
  }
  /**
   * Deletes a WorkloadIdentityPoolProvider. Deleting a provider does not revoke
   * credentials that have already been issued; they continue to grant access. You
   * can undelete a provider for 30 days. After 30 days, deletion is permanent.
   * You cannot update deleted providers. However, you can view and list them.
   * (providers.delete)
   *
   * @param string $name Required. The name of the provider to delete.
   * @param array $optParams Optional parameters.
   * @return Operation
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], Operation::class);
  }
  /**
   * Gets an individual WorkloadIdentityPoolProvider. (providers.get)
   *
   * @param string $name Required. The name of the provider to retrieve.
   * @param array $optParams Optional parameters.
   * @return WorkloadIdentityPoolProvider
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], WorkloadIdentityPoolProvider::class);
  }
  /**
   * Lists all non-deleted WorkloadIdentityPoolProviders in a
   * WorkloadIdentityPool. If `show_deleted` is set to `true`, then deleted
   * providers are also listed.
   * (providers.listProjectsLocationsWorkloadIdentityPoolsProviders)
   *
   * @param string $parent Required. The pool to list providers for.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int pageSize The maximum number of providers to return. If
   * unspecified, at most 50 providers are returned. The maximum value is 100;
   * values above 100 are truncated to 100.
   * @opt_param string pageToken A page token, received from a previous
   * `ListWorkloadIdentityPoolProviders` call. Provide this to retrieve the
   * subsequent page.
   * @opt_param bool showDeleted Whether to return soft-deleted providers.
   * @return ListWorkloadIdentityPoolProvidersResponse
   */
  public function listProjectsLocationsWorkloadIdentityPoolsProviders($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListWorkloadIdentityPoolProvidersResponse::class);
  }
  /**
   * Updates an existing WorkloadIdentityPoolProvider. (providers.patch)
   *
   * @param string $name Output only. The resource name of the provider.
   * @param WorkloadIdentityPoolProvider $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string updateMask Required. The list of fields to update.
   * @return Operation
   */
  public function patch($name, WorkloadIdentityPoolProvider $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], Operation::class);
  }
  /**
   * Undeletes a WorkloadIdentityPoolProvider, as long as it was deleted fewer
   * than 30 days ago. (providers.undelete)
   *
   * @param string $name Required. The name of the provider to undelete.
   * @param UndeleteWorkloadIdentityPoolProviderRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   */
  public function undelete($name, UndeleteWorkloadIdentityPoolProviderRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('undelete', [$params], Operation::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsWorkloadIdentityPoolsProviders::class, 'Google_Service_Iam_Resource_ProjectsLocationsWorkloadIdentityPoolsProviders');
