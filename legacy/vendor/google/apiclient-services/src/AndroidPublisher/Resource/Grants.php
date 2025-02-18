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

namespace Google\Service\AndroidPublisher\Resource;

use Google\Service\AndroidPublisher\Grant;

/**
 * The "grants" collection of methods.
 * Typical usage is:
 *  <code>
 *   $androidpublisherService = new Google\Service\AndroidPublisher(...);
 *   $grants = $androidpublisherService->grants;
 *  </code>
 */
class Grants extends \Google\Service\Resource
{
  /**
   * Grant access for a user to the given package. (grants.create)
   *
   * @param string $parent Required. The user which needs permission. Format:
   * developers/{developer}/users/{user}
   * @param Grant $postBody
   * @param array $optParams Optional parameters.
   * @return Grant
   * @throws \Google\Service\Exception
   */
  public function create($parent, Grant $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], Grant::class);
  }
  /**
   * Removes all access for the user to the given package or developer account.
   * (grants.delete)
   *
   * @param string $name Required. The name of the grant to delete. Format:
   * developers/{developer}/users/{email}/grants/{package_name}
   * @param array $optParams Optional parameters.
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params]);
  }
  /**
   * Updates access for the user to the given package. (grants.patch)
   *
   * @param string $name Required. Resource name for this grant, following the
   * pattern "developers/{developer}/users/{email}/grants/{package_name}". If this
   * grant is for a draft app, the app ID will be used in this resource name
   * instead of the package name.
   * @param Grant $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string updateMask Optional. The list of fields to be updated.
   * @return Grant
   * @throws \Google\Service\Exception
   */
  public function patch($name, Grant $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], Grant::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Grants::class, 'Google_Service_AndroidPublisher_Resource_Grants');
