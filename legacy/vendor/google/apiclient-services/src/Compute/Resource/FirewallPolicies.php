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

namespace Google\Service\Compute\Resource;

use Google\Service\Compute\FirewallPoliciesListAssociationsResponse;
use Google\Service\Compute\FirewallPolicy;
use Google\Service\Compute\FirewallPolicyAssociation;
use Google\Service\Compute\FirewallPolicyList;
use Google\Service\Compute\FirewallPolicyRule;
use Google\Service\Compute\GlobalOrganizationSetPolicyRequest;
use Google\Service\Compute\Operation;
use Google\Service\Compute\Policy;
use Google\Service\Compute\TestPermissionsRequest;
use Google\Service\Compute\TestPermissionsResponse;

/**
 * The "firewallPolicies" collection of methods.
 * Typical usage is:
 *  <code>
 *   $computeService = new Google\Service\Compute(...);
 *   $firewallPolicies = $computeService->firewallPolicies;
 *  </code>
 */
class FirewallPolicies extends \Google\Service\Resource
{
  /**
   * Inserts an association for the specified firewall policy.
   * (firewallPolicies.addAssociation)
   *
   * @param string $firewallPolicy Name of the firewall policy to update.
   * @param FirewallPolicyAssociation $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool replaceExistingAssociation Indicates whether or not to
   * replace it if an association of the attachment already exists. This is false
   * by default, in which case an error will be returned if an association already
   * exists.
   * @opt_param string requestId An optional request ID to identify requests.
   * Specify a unique request ID so that if you must retry your request, the
   * server will know to ignore the request if it has already been completed. For
   * example, consider a situation where you make an initial request and the
   * request times out. If you make the request again with the same request ID,
   * the server can check if original operation with the same request ID was
   * received, and if so, will ignore the second request. This prevents clients
   * from accidentally creating duplicate commitments. The request ID must be a
   * valid UUID with the exception that zero UUID is not supported (
   * 00000000-0000-0000-0000-000000000000).
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function addAssociation($firewallPolicy, FirewallPolicyAssociation $postBody, $optParams = [])
  {
    $params = ['firewallPolicy' => $firewallPolicy, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('addAssociation', [$params], Operation::class);
  }
  /**
   * Inserts a rule into a firewall policy. (firewallPolicies.addRule)
   *
   * @param string $firewallPolicy Name of the firewall policy to update.
   * @param FirewallPolicyRule $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string requestId An optional request ID to identify requests.
   * Specify a unique request ID so that if you must retry your request, the
   * server will know to ignore the request if it has already been completed. For
   * example, consider a situation where you make an initial request and the
   * request times out. If you make the request again with the same request ID,
   * the server can check if original operation with the same request ID was
   * received, and if so, will ignore the second request. This prevents clients
   * from accidentally creating duplicate commitments. The request ID must be a
   * valid UUID with the exception that zero UUID is not supported (
   * 00000000-0000-0000-0000-000000000000).
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function addRule($firewallPolicy, FirewallPolicyRule $postBody, $optParams = [])
  {
    $params = ['firewallPolicy' => $firewallPolicy, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('addRule', [$params], Operation::class);
  }
  /**
   * Copies rules to the specified firewall policy. (firewallPolicies.cloneRules)
   *
   * @param string $firewallPolicy Name of the firewall policy to update.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string requestId An optional request ID to identify requests.
   * Specify a unique request ID so that if you must retry your request, the
   * server will know to ignore the request if it has already been completed. For
   * example, consider a situation where you make an initial request and the
   * request times out. If you make the request again with the same request ID,
   * the server can check if original operation with the same request ID was
   * received, and if so, will ignore the second request. This prevents clients
   * from accidentally creating duplicate commitments. The request ID must be a
   * valid UUID with the exception that zero UUID is not supported (
   * 00000000-0000-0000-0000-000000000000).
   * @opt_param string sourceFirewallPolicy The firewall policy from which to copy
   * rules.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function cloneRules($firewallPolicy, $optParams = [])
  {
    $params = ['firewallPolicy' => $firewallPolicy];
    $params = array_merge($params, $optParams);
    return $this->call('cloneRules', [$params], Operation::class);
  }
  /**
   * Deletes the specified policy. (firewallPolicies.delete)
   *
   * @param string $firewallPolicy Name of the firewall policy to delete.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string requestId An optional request ID to identify requests.
   * Specify a unique request ID so that if you must retry your request, the
   * server will know to ignore the request if it has already been completed. For
   * example, consider a situation where you make an initial request and the
   * request times out. If you make the request again with the same request ID,
   * the server can check if original operation with the same request ID was
   * received, and if so, will ignore the second request. This prevents clients
   * from accidentally creating duplicate commitments. The request ID must be a
   * valid UUID with the exception that zero UUID is not supported (
   * 00000000-0000-0000-0000-000000000000).
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function delete($firewallPolicy, $optParams = [])
  {
    $params = ['firewallPolicy' => $firewallPolicy];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], Operation::class);
  }
  /**
   * Returns the specified firewall policy. (firewallPolicies.get)
   *
   * @param string $firewallPolicy Name of the firewall policy to get.
   * @param array $optParams Optional parameters.
   * @return FirewallPolicy
   * @throws \Google\Service\Exception
   */
  public function get($firewallPolicy, $optParams = [])
  {
    $params = ['firewallPolicy' => $firewallPolicy];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], FirewallPolicy::class);
  }
  /**
   * Gets an association with the specified name.
   * (firewallPolicies.getAssociation)
   *
   * @param string $firewallPolicy Name of the firewall policy to which the
   * queried rule belongs.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string name The name of the association to get from the firewall
   * policy.
   * @return FirewallPolicyAssociation
   * @throws \Google\Service\Exception
   */
  public function getAssociation($firewallPolicy, $optParams = [])
  {
    $params = ['firewallPolicy' => $firewallPolicy];
    $params = array_merge($params, $optParams);
    return $this->call('getAssociation', [$params], FirewallPolicyAssociation::class);
  }
  /**
   * Gets the access control policy for a resource. May be empty if no such policy
   * or resource exists. (firewallPolicies.getIamPolicy)
   *
   * @param string $resource Name or id of the resource for this request.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int optionsRequestedPolicyVersion Requested IAM Policy version.
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
   * Gets a rule of the specified priority. (firewallPolicies.getRule)
   *
   * @param string $firewallPolicy Name of the firewall policy to which the
   * queried rule belongs.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int priority The priority of the rule to get from the firewall
   * policy.
   * @return FirewallPolicyRule
   * @throws \Google\Service\Exception
   */
  public function getRule($firewallPolicy, $optParams = [])
  {
    $params = ['firewallPolicy' => $firewallPolicy];
    $params = array_merge($params, $optParams);
    return $this->call('getRule', [$params], FirewallPolicyRule::class);
  }
  /**
   * Creates a new policy in the specified project using the data included in the
   * request. (firewallPolicies.insert)
   *
   * @param FirewallPolicy $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string parentId Parent ID for this request. The ID can be either
   * be "folders/[FOLDER_ID]" if the parent is a folder or
   * "organizations/[ORGANIZATION_ID]" if the parent is an organization.
   * @opt_param string requestId An optional request ID to identify requests.
   * Specify a unique request ID so that if you must retry your request, the
   * server will know to ignore the request if it has already been completed. For
   * example, consider a situation where you make an initial request and the
   * request times out. If you make the request again with the same request ID,
   * the server can check if original operation with the same request ID was
   * received, and if so, will ignore the second request. This prevents clients
   * from accidentally creating duplicate commitments. The request ID must be a
   * valid UUID with the exception that zero UUID is not supported (
   * 00000000-0000-0000-0000-000000000000).
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function insert(FirewallPolicy $postBody, $optParams = [])
  {
    $params = ['postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('insert', [$params], Operation::class);
  }
  /**
   * Lists all the policies that have been configured for the specified folder or
   * organization. (firewallPolicies.listFirewallPolicies)
   *
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter A filter expression that filters resources listed in
   * the response. Most Compute resources support two types of filter expressions:
   * expressions that support regular expressions and expressions that follow API
   * improvement proposal AIP-160. These two types of filter expressions cannot be
   * mixed in one request. If you want to use AIP-160, your expression must
   * specify the field name, an operator, and the value that you want to use for
   * filtering. The value must be a string, a number, or a boolean. The operator
   * must be either `=`, `!=`, `>`, `<`, `<=`, `>=` or `:`. For example, if you
   * are filtering Compute Engine instances, you can exclude instances named
   * `example-instance` by specifying `name != example-instance`. The `:*`
   * comparison can be used to test whether a key has been defined. For example,
   * to find all objects with `owner` label use: ``` labels.owner:* ``` You can
   * also filter nested fields. For example, you could specify
   * `scheduling.automaticRestart = false` to include instances only if they are
   * not scheduled for automatic restarts. You can use filtering on nested fields
   * to filter based on resource labels. To filter on multiple expressions,
   * provide each separate expression within parentheses. For example: ```
   * (scheduling.automaticRestart = true) (cpuPlatform = "Intel Skylake") ``` By
   * default, each expression is an `AND` expression. However, you can include
   * `AND` and `OR` expressions explicitly. For example: ``` (cpuPlatform = "Intel
   * Skylake") OR (cpuPlatform = "Intel Broadwell") AND
   * (scheduling.automaticRestart = true) ``` If you want to use a regular
   * expression, use the `eq` (equal) or `ne` (not equal) operator against a
   * single un-parenthesized expression with or without quotes or against multiple
   * parenthesized expressions. Examples: `fieldname eq unquoted literal`
   * `fieldname eq 'single quoted literal'` `fieldname eq "double quoted literal"`
   * `(fieldname1 eq literal) (fieldname2 ne "literal")` The literal value is
   * interpreted as a regular expression using Google RE2 library syntax. The
   * literal value must match the entire field. For example, to filter for
   * instances that do not end with name "instance", you would use `name ne
   * .*instance`. You cannot combine constraints on multiple fields using regular
   * expressions.
   * @opt_param string maxResults The maximum number of results per page that
   * should be returned. If the number of available results is larger than
   * `maxResults`, Compute Engine returns a `nextPageToken` that can be used to
   * get the next page of results in subsequent list requests. Acceptable values
   * are `0` to `500`, inclusive. (Default: `500`)
   * @opt_param string orderBy Sorts list results by a certain order. By default,
   * results are returned in alphanumerical order based on the resource name. You
   * can also sort results in descending order based on the creation timestamp
   * using `orderBy="creationTimestamp desc"`. This sorts results based on the
   * `creationTimestamp` field in reverse chronological order (newest result
   * first). Use this to sort resources like operations so that the newest
   * operation is returned first. Currently, only sorting by `name` or
   * `creationTimestamp desc` is supported.
   * @opt_param string pageToken Specifies a page token to use. Set `pageToken` to
   * the `nextPageToken` returned by a previous list request to get the next page
   * of results.
   * @opt_param string parentId Parent ID for this request. The ID can be either
   * be "folders/[FOLDER_ID]" if the parent is a folder or
   * "organizations/[ORGANIZATION_ID]" if the parent is an organization.
   * @opt_param bool returnPartialSuccess Opt-in for partial success behavior
   * which provides partial results in case of failure. The default value is
   * false. For example, when partial success behavior is enabled, aggregatedList
   * for a single zone scope either returns all resources in the zone or no
   * resources, with an error code.
   * @return FirewallPolicyList
   * @throws \Google\Service\Exception
   */
  public function listFirewallPolicies($optParams = [])
  {
    $params = [];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], FirewallPolicyList::class);
  }
  /**
   * Lists associations of a specified target, i.e., organization or folder.
   * (firewallPolicies.listAssociations)
   *
   * @param array $optParams Optional parameters.
   *
   * @opt_param string targetResource The target resource to list associations. It
   * is an organization, or a folder.
   * @return FirewallPoliciesListAssociationsResponse
   * @throws \Google\Service\Exception
   */
  public function listAssociations($optParams = [])
  {
    $params = [];
    $params = array_merge($params, $optParams);
    return $this->call('listAssociations', [$params], FirewallPoliciesListAssociationsResponse::class);
  }
  /**
   * Moves the specified firewall policy. (firewallPolicies.move)
   *
   * @param string $firewallPolicy Name of the firewall policy to update.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string parentId The new parent of the firewall policy. The ID can
   * be either be "folders/[FOLDER_ID]" if the parent is a folder or
   * "organizations/[ORGANIZATION_ID]" if the parent is an organization.
   * @opt_param string requestId An optional request ID to identify requests.
   * Specify a unique request ID so that if you must retry your request, the
   * server will know to ignore the request if it has already been completed. For
   * example, consider a situation where you make an initial request and the
   * request times out. If you make the request again with the same request ID,
   * the server can check if original operation with the same request ID was
   * received, and if so, will ignore the second request. This prevents clients
   * from accidentally creating duplicate commitments. The request ID must be a
   * valid UUID with the exception that zero UUID is not supported (
   * 00000000-0000-0000-0000-000000000000).
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function move($firewallPolicy, $optParams = [])
  {
    $params = ['firewallPolicy' => $firewallPolicy];
    $params = array_merge($params, $optParams);
    return $this->call('move', [$params], Operation::class);
  }
  /**
   * Patches the specified policy with the data included in the request.
   * (firewallPolicies.patch)
   *
   * @param string $firewallPolicy Name of the firewall policy to update.
   * @param FirewallPolicy $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string requestId An optional request ID to identify requests.
   * Specify a unique request ID so that if you must retry your request, the
   * server will know to ignore the request if it has already been completed. For
   * example, consider a situation where you make an initial request and the
   * request times out. If you make the request again with the same request ID,
   * the server can check if original operation with the same request ID was
   * received, and if so, will ignore the second request. This prevents clients
   * from accidentally creating duplicate commitments. The request ID must be a
   * valid UUID with the exception that zero UUID is not supported (
   * 00000000-0000-0000-0000-000000000000).
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function patch($firewallPolicy, FirewallPolicy $postBody, $optParams = [])
  {
    $params = ['firewallPolicy' => $firewallPolicy, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], Operation::class);
  }
  /**
   * Patches a rule of the specified priority. (firewallPolicies.patchRule)
   *
   * @param string $firewallPolicy Name of the firewall policy to update.
   * @param FirewallPolicyRule $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param int priority The priority of the rule to patch.
   * @opt_param string requestId An optional request ID to identify requests.
   * Specify a unique request ID so that if you must retry your request, the
   * server will know to ignore the request if it has already been completed. For
   * example, consider a situation where you make an initial request and the
   * request times out. If you make the request again with the same request ID,
   * the server can check if original operation with the same request ID was
   * received, and if so, will ignore the second request. This prevents clients
   * from accidentally creating duplicate commitments. The request ID must be a
   * valid UUID with the exception that zero UUID is not supported (
   * 00000000-0000-0000-0000-000000000000).
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function patchRule($firewallPolicy, FirewallPolicyRule $postBody, $optParams = [])
  {
    $params = ['firewallPolicy' => $firewallPolicy, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patchRule', [$params], Operation::class);
  }
  /**
   * Removes an association for the specified firewall policy.
   * (firewallPolicies.removeAssociation)
   *
   * @param string $firewallPolicy Name of the firewall policy to update.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string name Name for the attachment that will be removed.
   * @opt_param string requestId An optional request ID to identify requests.
   * Specify a unique request ID so that if you must retry your request, the
   * server will know to ignore the request if it has already been completed. For
   * example, consider a situation where you make an initial request and the
   * request times out. If you make the request again with the same request ID,
   * the server can check if original operation with the same request ID was
   * received, and if so, will ignore the second request. This prevents clients
   * from accidentally creating duplicate commitments. The request ID must be a
   * valid UUID with the exception that zero UUID is not supported (
   * 00000000-0000-0000-0000-000000000000).
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function removeAssociation($firewallPolicy, $optParams = [])
  {
    $params = ['firewallPolicy' => $firewallPolicy];
    $params = array_merge($params, $optParams);
    return $this->call('removeAssociation', [$params], Operation::class);
  }
  /**
   * Deletes a rule of the specified priority. (firewallPolicies.removeRule)
   *
   * @param string $firewallPolicy Name of the firewall policy to update.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int priority The priority of the rule to remove from the firewall
   * policy.
   * @opt_param string requestId An optional request ID to identify requests.
   * Specify a unique request ID so that if you must retry your request, the
   * server will know to ignore the request if it has already been completed. For
   * example, consider a situation where you make an initial request and the
   * request times out. If you make the request again with the same request ID,
   * the server can check if original operation with the same request ID was
   * received, and if so, will ignore the second request. This prevents clients
   * from accidentally creating duplicate commitments. The request ID must be a
   * valid UUID with the exception that zero UUID is not supported (
   * 00000000-0000-0000-0000-000000000000).
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function removeRule($firewallPolicy, $optParams = [])
  {
    $params = ['firewallPolicy' => $firewallPolicy];
    $params = array_merge($params, $optParams);
    return $this->call('removeRule', [$params], Operation::class);
  }
  /**
   * Sets the access control policy on the specified resource. Replaces any
   * existing policy. (firewallPolicies.setIamPolicy)
   *
   * @param string $resource Name or id of the resource for this request.
   * @param GlobalOrganizationSetPolicyRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Policy
   * @throws \Google\Service\Exception
   */
  public function setIamPolicy($resource, GlobalOrganizationSetPolicyRequest $postBody, $optParams = [])
  {
    $params = ['resource' => $resource, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('setIamPolicy', [$params], Policy::class);
  }
  /**
   * Returns permissions that a caller has on the specified resource.
   * (firewallPolicies.testIamPermissions)
   *
   * @param string $resource Name or id of the resource for this request.
   * @param TestPermissionsRequest $postBody
   * @param array $optParams Optional parameters.
   * @return TestPermissionsResponse
   * @throws \Google\Service\Exception
   */
  public function testIamPermissions($resource, TestPermissionsRequest $postBody, $optParams = [])
  {
    $params = ['resource' => $resource, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('testIamPermissions', [$params], TestPermissionsResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(FirewallPolicies::class, 'Google_Service_Compute_Resource_FirewallPolicies');
