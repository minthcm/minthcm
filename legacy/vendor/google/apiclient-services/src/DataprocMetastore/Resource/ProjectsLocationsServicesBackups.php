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

namespace Google\Service\DataprocMetastore\Resource;

use Google\Service\DataprocMetastore\Backup;
use Google\Service\DataprocMetastore\ListBackupsResponse;
use Google\Service\DataprocMetastore\Operation;
use Google\Service\DataprocMetastore\Policy;
use Google\Service\DataprocMetastore\SetIamPolicyRequest;

/**
 * The "backups" collection of methods.
 * Typical usage is:
 *  <code>
 *   $metastoreService = new Google\Service\DataprocMetastore(...);
 *   $backups = $metastoreService->projects_locations_services_backups;
 *  </code>
 */
class ProjectsLocationsServicesBackups extends \Google\Service\Resource
{
  /**
   * Creates a new backup in a given project and location. (backups.create)
   *
   * @param string $parent Required. The relative resource name of the service in
   * which to create a backup of the following
   * form:projects/{project_number}/locations/{location_id}/services/{service_id}.
   * @param Backup $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string backupId Required. The ID of the backup, which is used as
   * the final component of the backup's name.This value must be between 1 and 64
   * characters long, begin with a letter, end with a letter or number, and
   * consist of alpha-numeric ASCII characters or hyphens.
   * @opt_param string requestId Optional. A request ID. Specify a unique request
   * ID to allow the server to ignore the request if it has completed. The server
   * will ignore subsequent requests that provide a duplicate request ID for at
   * least 60 minutes after the first request.For example, if an initial request
   * times out, followed by another request with the same request ID, the server
   * ignores the second request to prevent the creation of duplicate
   * commitments.The request ID must be a valid UUID
   * (https://en.wikipedia.org/wiki/Universally_unique_identifier#Format) A zero
   * UUID (00000000-0000-0000-0000-000000000000) is not supported.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function create($parent, Backup $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], Operation::class);
  }
  /**
   * Deletes a single backup. (backups.delete)
   *
   * @param string $name Required. The relative resource name of the backup to
   * delete, in the following form:projects/{project_number}/locations/{location_i
   * d}/services/{service_id}/backups/{backup_id}.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string requestId Optional. A request ID. Specify a unique request
   * ID to allow the server to ignore the request if it has completed. The server
   * will ignore subsequent requests that provide a duplicate request ID for at
   * least 60 minutes after the first request.For example, if an initial request
   * times out, followed by another request with the same request ID, the server
   * ignores the second request to prevent the creation of duplicate
   * commitments.The request ID must be a valid UUID
   * (https://en.wikipedia.org/wiki/Universally_unique_identifier#Format) A zero
   * UUID (00000000-0000-0000-0000-000000000000) is not supported.
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
   * Gets details of a single backup. (backups.get)
   *
   * @param string $name Required. The relative resource name of the backup to
   * retrieve, in the following form:projects/{project_number}/locations/{location
   * _id}/services/{service_id}/backups/{backup_id}.
   * @param array $optParams Optional parameters.
   * @return Backup
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Backup::class);
  }
  /**
   * Gets the access control policy for a resource. Returns an empty policy if the
   * resource exists and does not have a policy set. (backups.getIamPolicy)
   *
   * @param string $resource REQUIRED: The resource for which the policy is being
   * requested. See Resource names
   * (https://cloud.google.com/apis/design/resource_names) for the appropriate
   * value for this field.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int options.requestedPolicyVersion Optional. The maximum policy
   * version that will be used to format the policy.Valid values are 0, 1, and 3.
   * Requests specifying an invalid value will be rejected.Requests for policies
   * with any conditional role bindings must specify version 3. Policies with no
   * conditional role bindings may specify any valid value or leave the field
   * unset.The policy in the response might use the policy version that you
   * specified, or it might use a lower policy version. For example, if you
   * specify version 3, but the policy has no conditional role bindings, the
   * response uses version 1.To learn which resources support conditions in their
   * IAM policies, see the IAM documentation
   * (https://cloud.google.com/iam/help/conditions/resource-policies).
   * @return Policy
   * @throws \Google\Service\Exception
   */
  public function getIamPolicy($resource, $optParams = [])
  {
    $params = ['resource' => $resource];
    $params = array_merge($params, $optParams);
    return $this->call('getIamPolicy', [$params], Policy::class);
  }
  /**
   * Lists backups in a service. (backups.listProjectsLocationsServicesBackups)
   *
   * @param string $parent Required. The relative resource name of the service
   * whose backups to list, in the following form:projects/{project_number}/locati
   * ons/{location_id}/services/{service_id}/backups.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter Optional. The filter to apply to list results.
   * @opt_param string orderBy Optional. Specify the ordering of results as
   * described in Sorting Order
   * (https://cloud.google.com/apis/design/design_patterns#sorting_order). If not
   * specified, the results will be sorted in the default order.
   * @opt_param int pageSize Optional. The maximum number of backups to return.
   * The response may contain less than the maximum number. If unspecified, no
   * more than 500 backups are returned. The maximum value is 1000; values above
   * 1000 are changed to 1000.
   * @opt_param string pageToken Optional. A page token, received from a previous
   * DataprocMetastore.ListBackups call. Provide this token to retrieve the
   * subsequent page.To retrieve the first page, supply an empty page token.When
   * paginating, other parameters provided to DataprocMetastore.ListBackups must
   * match the call that provided the page token.
   * @return ListBackupsResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsLocationsServicesBackups($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListBackupsResponse::class);
  }
  /**
   * Sets the access control policy on the specified resource. Replaces any
   * existing policy.Can return NOT_FOUND, INVALID_ARGUMENT, and PERMISSION_DENIED
   * errors. (backups.setIamPolicy)
   *
   * @param string $resource REQUIRED: The resource for which the policy is being
   * specified. See Resource names
   * (https://cloud.google.com/apis/design/resource_names) for the appropriate
   * value for this field.
   * @param SetIamPolicyRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Policy
   * @throws \Google\Service\Exception
   */
  public function setIamPolicy($resource, SetIamPolicyRequest $postBody, $optParams = [])
  {
    $params = ['resource' => $resource, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('setIamPolicy', [$params], Policy::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsServicesBackups::class, 'Google_Service_DataprocMetastore_Resource_ProjectsLocationsServicesBackups');
