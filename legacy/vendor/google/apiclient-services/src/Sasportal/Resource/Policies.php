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

namespace Google\Service\Sasportal\Resource;

use Google\Service\Sasportal\SasPortalGetPolicyRequest;
use Google\Service\Sasportal\SasPortalPolicy;
use Google\Service\Sasportal\SasPortalSetPolicyRequest;
use Google\Service\Sasportal\SasPortalTestPermissionsRequest;
use Google\Service\Sasportal\SasPortalTestPermissionsResponse;

/**
 * The "policies" collection of methods.
 * Typical usage is:
 *  <code>
 *   $sasportalService = new Google\Service\Sasportal(...);
 *   $policies = $sasportalService->policies;
 *  </code>
 */
class Policies extends \Google\Service\Resource
{
  /**
   * Gets the access control policy for a resource. Returns an empty policy if the
   * resource exists and does not have a policy set. (policies.get)
   *
   * @param SasPortalGetPolicyRequest $postBody
   * @param array $optParams Optional parameters.
   * @return SasPortalPolicy
   * @throws \Google\Service\Exception
   */
  public function get(SasPortalGetPolicyRequest $postBody, $optParams = [])
  {
    $params = ['postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], SasPortalPolicy::class);
  }
  /**
   * Sets the access control policy on the specified resource. Replaces any
   * existing policy. (policies.set)
   *
   * @param SasPortalSetPolicyRequest $postBody
   * @param array $optParams Optional parameters.
   * @return SasPortalPolicy
   * @throws \Google\Service\Exception
   */
  public function set(SasPortalSetPolicyRequest $postBody, $optParams = [])
  {
    $params = ['postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('set', [$params], SasPortalPolicy::class);
  }
  /**
   * Returns permissions that a caller has on the specified resource.
   * (policies.test)
   *
   * @param SasPortalTestPermissionsRequest $postBody
   * @param array $optParams Optional parameters.
   * @return SasPortalTestPermissionsResponse
   * @throws \Google\Service\Exception
   */
  public function test(SasPortalTestPermissionsRequest $postBody, $optParams = [])
  {
    $params = ['postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('test', [$params], SasPortalTestPermissionsResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Policies::class, 'Google_Service_Sasportal_Resource_Policies');
