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

namespace Google\Service\NetworkSecurity\Resource;

use Google\Service\NetworkSecurity\GoogleIamV1Policy;
use Google\Service\NetworkSecurity\GoogleIamV1SetIamPolicyRequest;
use Google\Service\NetworkSecurity\GoogleIamV1TestIamPermissionsRequest;
use Google\Service\NetworkSecurity\GoogleIamV1TestIamPermissionsResponse;
use Google\Service\NetworkSecurity\ListServerTlsPoliciesResponse;
use Google\Service\NetworkSecurity\Operation;
use Google\Service\NetworkSecurity\ServerTlsPolicy;

/**
 * The "serverTlsPolicies" collection of methods.
 * Typical usage is:
 *  <code>
 *   $networksecurityService = new Google\Service\NetworkSecurity(...);
 *   $serverTlsPolicies = $networksecurityService->projects_locations_serverTlsPolicies;
 *  </code>
 */
class ProjectsLocationsServerTlsPolicies extends \Google\Service\Resource
{
  /**
   * Creates a new ServerTlsPolicy in a given project and location.
   * (serverTlsPolicies.create)
   *
   * @param string $parent Required. The parent resource of the ServerTlsPolicy.
   * Must be in the format `projects/locations/{location}`.
   * @param ServerTlsPolicy $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string serverTlsPolicyId Required. Short name of the
   * ServerTlsPolicy resource to be created. This value should be 1-63 characters
   * long, containing only letters, numbers, hyphens, and underscores, and should
   * not start with a number. E.g. "server_mtls_policy".
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function create($parent, ServerTlsPolicy $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], Operation::class);
  }
  /**
   * Deletes a single ServerTlsPolicy. (serverTlsPolicies.delete)
   *
   * @param string $name Required. A name of the ServerTlsPolicy to delete. Must
   * be in the format `projects/locations/{location}/serverTlsPolicies`.
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
   * Gets details of a single ServerTlsPolicy. (serverTlsPolicies.get)
   *
   * @param string $name Required. A name of the ServerTlsPolicy to get. Must be
   * in the format `projects/locations/{location}/serverTlsPolicies`.
   * @param array $optParams Optional parameters.
   * @return ServerTlsPolicy
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], ServerTlsPolicy::class);
  }
  /**
   * Gets the access control policy for a resource. Returns an empty policy if the
   * resource exists and does not have a policy set.
   * (serverTlsPolicies.getIamPolicy)
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
   * @return GoogleIamV1Policy
   * @throws \Google\Service\Exception
   */
  public function getIamPolicy($resource, $optParams = [])
  {
    $params = ['resource' => $resource];
    $params = array_merge($params, $optParams);
    return $this->call('getIamPolicy', [$params], GoogleIamV1Policy::class);
  }
  /**
   * Lists ServerTlsPolicies in a given project and location.
   * (serverTlsPolicies.listProjectsLocationsServerTlsPolicies)
   *
   * @param string $parent Required. The project and location from which the
   * ServerTlsPolicies should be listed, specified in the format
   * `projects/locations/{location}`.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int pageSize Maximum number of ServerTlsPolicies to return per
   * call.
   * @opt_param string pageToken The value returned by the last
   * `ListServerTlsPoliciesResponse` Indicates that this is a continuation of a
   * prior `ListServerTlsPolicies` call, and that the system should return the
   * next page of data.
   * @return ListServerTlsPoliciesResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsLocationsServerTlsPolicies($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListServerTlsPoliciesResponse::class);
  }
  /**
   * Updates the parameters of a single ServerTlsPolicy. (serverTlsPolicies.patch)
   *
   * @param string $name Required. Name of the ServerTlsPolicy resource. It
   * matches the pattern
   * `projects/locations/{location}/serverTlsPolicies/{server_tls_policy}`
   * @param ServerTlsPolicy $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string updateMask Optional. Field mask is used to specify the
   * fields to be overwritten in the ServerTlsPolicy resource by the update. The
   * fields specified in the update_mask are relative to the resource, not the
   * full request. A field will be overwritten if it is in the mask. If the user
   * does not provide a mask then all fields will be overwritten.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function patch($name, ServerTlsPolicy $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], Operation::class);
  }
  /**
   * Sets the access control policy on the specified resource. Replaces any
   * existing policy. Can return `NOT_FOUND`, `INVALID_ARGUMENT`, and
   * `PERMISSION_DENIED` errors. (serverTlsPolicies.setIamPolicy)
   *
   * @param string $resource REQUIRED: The resource for which the policy is being
   * specified. See [Resource
   * names](https://cloud.google.com/apis/design/resource_names) for the
   * appropriate value for this field.
   * @param GoogleIamV1SetIamPolicyRequest $postBody
   * @param array $optParams Optional parameters.
   * @return GoogleIamV1Policy
   * @throws \Google\Service\Exception
   */
  public function setIamPolicy($resource, GoogleIamV1SetIamPolicyRequest $postBody, $optParams = [])
  {
    $params = ['resource' => $resource, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('setIamPolicy', [$params], GoogleIamV1Policy::class);
  }
  /**
   * Returns permissions that a caller has on the specified resource. If the
   * resource does not exist, this will return an empty set of permissions, not a
   * `NOT_FOUND` error. Note: This operation is designed to be used for building
   * permission-aware UIs and command-line tools, not for authorization checking.
   * This operation may "fail open" without warning.
   * (serverTlsPolicies.testIamPermissions)
   *
   * @param string $resource REQUIRED: The resource for which the policy detail is
   * being requested. See [Resource
   * names](https://cloud.google.com/apis/design/resource_names) for the
   * appropriate value for this field.
   * @param GoogleIamV1TestIamPermissionsRequest $postBody
   * @param array $optParams Optional parameters.
   * @return GoogleIamV1TestIamPermissionsResponse
   * @throws \Google\Service\Exception
   */
  public function testIamPermissions($resource, GoogleIamV1TestIamPermissionsRequest $postBody, $optParams = [])
  {
    $params = ['resource' => $resource, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('testIamPermissions', [$params], GoogleIamV1TestIamPermissionsResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsServerTlsPolicies::class, 'Google_Service_NetworkSecurity_Resource_ProjectsLocationsServerTlsPolicies');
