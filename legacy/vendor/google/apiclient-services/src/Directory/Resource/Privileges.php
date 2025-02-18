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

namespace Google\Service\Directory\Resource;

use Google\Service\Directory\Privileges as PrivilegesModel;

/**
 * The "privileges" collection of methods.
 * Typical usage is:
 *  <code>
 *   $adminService = new Google\Service\Directory(...);
 *   $privileges = $adminService->privileges;
 *  </code>
 */
class Privileges extends \Google\Service\Resource
{
  /**
   * Retrieves a paginated list of all privileges for a customer.
   * (privileges.listPrivileges)
   *
   * @param string $customer The unique ID for the customer's Google Workspace
   * account. In case of a multi-domain account, to fetch all groups for a
   * customer, use this field instead of `domain`. You can also use the
   * `my_customer` alias to represent your account's `customerId`. The
   * `customerId` is also returned as part of the [Users](/admin-
   * sdk/directory/v1/reference/users) resource. You must provide either the
   * `customer` or the `domain` parameter.
   * @param array $optParams Optional parameters.
   * @return PrivilegesModel
   * @throws \Google\Service\Exception
   */
  public function listPrivileges($customer, $optParams = [])
  {
    $params = ['customer' => $customer];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], PrivilegesModel::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Privileges::class, 'Google_Service_Directory_Resource_Privileges');
