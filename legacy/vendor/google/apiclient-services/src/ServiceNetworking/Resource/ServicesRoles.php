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

namespace Google\Service\ServiceNetworking\Resource;

use Google\Service\ServiceNetworking\AddRolesRequest;
use Google\Service\ServiceNetworking\Operation;

/**
 * The "roles" collection of methods.
 * Typical usage is:
 *  <code>
 *   $servicenetworkingService = new Google\Service\ServiceNetworking(...);
 *   $roles = $servicenetworkingService->services_roles;
 *  </code>
 */
class ServicesRoles extends \Google\Service\Resource
{
  /**
   * Service producers can use this method to add roles in the shared VPC host
   * project. Each role is bound to the provided member. Each role must be
   * selected from within an allowlisted set of roles. Each role is applied at
   * only the granularity specified in the allowlist. (roles.add)
   *
   * @param string $parent Required. This is in a form services/{service} where
   * {service} is the name of the private access management service. For example
   * 'service-peering.example.com'.
   * @param AddRolesRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function add($parent, AddRolesRequest $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('add', [$params], Operation::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ServicesRoles::class, 'Google_Service_ServiceNetworking_Resource_ServicesRoles');
