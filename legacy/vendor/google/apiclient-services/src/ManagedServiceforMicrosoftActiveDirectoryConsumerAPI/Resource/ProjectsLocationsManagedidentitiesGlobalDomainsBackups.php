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

namespace Google\Service\ManagedServiceforMicrosoftActiveDirectoryConsumerAPI\Resource;

use Google\Service\ManagedServiceforMicrosoftActiveDirectoryConsumerAPI\Backup;
use Google\Service\ManagedServiceforMicrosoftActiveDirectoryConsumerAPI\ListBackupsResponse;
use Google\Service\ManagedServiceforMicrosoftActiveDirectoryConsumerAPI\Operation;
use Google\Service\ManagedServiceforMicrosoftActiveDirectoryConsumerAPI\Policy;
use Google\Service\ManagedServiceforMicrosoftActiveDirectoryConsumerAPI\SetIamPolicyRequest;
use Google\Service\ManagedServiceforMicrosoftActiveDirectoryConsumerAPI\TestIamPermissionsRequest;
use Google\Service\ManagedServiceforMicrosoftActiveDirectoryConsumerAPI\TestIamPermissionsResponse;

/**
 * The "backups" collection of methods.
 * Typical usage is:
 *  <code>
 *   $managedidentitiesService = new Google\Service\ManagedServiceforMicrosoftActiveDirectoryConsumerAPI(...);
 *   $backups = $managedidentitiesService->projects_locations_global_domains_backups;
 *  </code>
 */
class ProjectsLocationsManagedidentitiesGlobalDomainsBackups extends \Google\Service\Resource
{
  /**
   * Creates a Backup for a domain. (backups.create)
   *
   * @param string $parent Required. The domain resource name using the form:
   * `projects/{project_id}/locations/global/domains/{domain_name}`
   * @param Backup $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string backupId Required. Backup Id, unique name to identify the
   * backups with the following restrictions: * Must be lowercase letters,
   * numbers, and hyphens * Must start with a letter. * Must contain between 1-63
   * characters. * Must end with a number or a letter. * Must be unique within the
   * domain.
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
   * Deletes identified Backup. (backups.delete)
   *
   * @param string $name Required. The backup resource name using the form: `proje
   * cts/{project_id}/locations/global/domains/{domain_name}/backups/{backup_id}`
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
   * Gets details of a single Backup. (backups.get)
   *
   * @param string $name Required. The backup resource name using the form: `proje
   * cts/{project_id}/locations/global/domains/{domain_name}/backups/{backup_id}`
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
   * requested. See [Resource
   * names](https://cloud.google.com/apis/design/resource_names) for the
   * appropriate value for this field.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int options.requestedPolicyVersion Optional. The maximum policy
   * version that will be used to format the policy. Valid values are 0, 1, and 3.
   * Requests specifying an invalid value will be rejected. Requests for policies
   * with any conditional role bindings must specify version 3. Policies with no
   * conditional role bindings may specify any valid value or leave the field
   * unset. The policy in the response might use the policy version that you
   * specified, or it might use a lower policy version. For example, if you
   * specify version 3, but the policy has no conditional role bindings, the
   * response uses version 1. To learn which resources support conditions in their
   * IAM policies, see the [IAM
   * documentation](https://cloud.google.com/iam/help/conditions/resource-
   * policies).
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
   * Lists Backup in a given project.
   * (backups.listProjectsLocationsManagedidentitiesGlobalDomainsBackups)
   *
   * @param string $parent Required. The domain resource name using the form:
   * `projects/{project_id}/locations/global/domains/{domain_name}`
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter Optional. Filter specifying constraints of a list
   * operation.
   * @opt_param string orderBy Optional. Specifies the ordering of results
   * following syntax at
   * https://cloud.google.com/apis/design/design_patterns#sorting_order.
   * @opt_param int pageSize Optional. The maximum number of items to return. If
   * not specified, a default value of 1000 will be used by the service.
   * Regardless of the page_size value, the response may include a partial list
   * and a caller should only rely on response's next_page_token to determine if
   * there are more instances left to be queried.
   * @opt_param string pageToken Optional. The `next_page_token` value returned
   * from a previous List request, if any.
   * @return ListBackupsResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsLocationsManagedidentitiesGlobalDomainsBackups($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListBackupsResponse::class);
  }
  /**
   * Updates the labels for specified Backup. (backups.patch)
   *
   * @param string $name Output only. The unique name of the Backup in the form of
   * `projects/{project_id}/locations/global/domains/{domain_name}/backups/{name}`
   * @param Backup $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string updateMask Required. Mask of fields to update. At least one
   * path must be supplied in this field. The elements of the repeated paths field
   * may only include these fields from Backup: * `labels`
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function patch($name, Backup $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], Operation::class);
  }
  /**
   * Sets the access control policy on the specified resource. Replaces any
   * existing policy. Can return `NOT_FOUND`, `INVALID_ARGUMENT`, and
   * `PERMISSION_DENIED` errors. (backups.setIamPolicy)
   *
   * @param string $resource REQUIRED: The resource for which the policy is being
   * specified. See [Resource
   * names](https://cloud.google.com/apis/design/resource_names) for the
   * appropriate value for this field.
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
  /**
   * Returns permissions that a caller has on the specified resource. If the
   * resource does not exist, this will return an empty set of permissions, not a
   * `NOT_FOUND` error. Note: This operation is designed to be used for building
   * permission-aware UIs and command-line tools, not for authorization checking.
   * This operation may "fail open" without warning. (backups.testIamPermissions)
   *
   * @param string $resource REQUIRED: The resource for which the policy detail is
   * being requested. See [Resource
   * names](https://cloud.google.com/apis/design/resource_names) for the
   * appropriate value for this field.
   * @param TestIamPermissionsRequest $postBody
   * @param array $optParams Optional parameters.
   * @return TestIamPermissionsResponse
   * @throws \Google\Service\Exception
   */
  public function testIamPermissions($resource, TestIamPermissionsRequest $postBody, $optParams = [])
  {
    $params = ['resource' => $resource, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('testIamPermissions', [$params], TestIamPermissionsResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsManagedidentitiesGlobalDomainsBackups::class, 'Google_Service_ManagedServiceforMicrosoftActiveDirectoryConsumerAPI_Resource_ProjectsLocationsManagedidentitiesGlobalDomainsBackups');
