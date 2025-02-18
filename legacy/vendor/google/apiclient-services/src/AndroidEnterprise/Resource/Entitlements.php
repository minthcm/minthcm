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

namespace Google\Service\AndroidEnterprise\Resource;

use Google\Service\AndroidEnterprise\Entitlement;
use Google\Service\AndroidEnterprise\EntitlementsListResponse;

/**
 * The "entitlements" collection of methods.
 * Typical usage is:
 *  <code>
 *   $androidenterpriseService = new Google\Service\AndroidEnterprise(...);
 *   $entitlements = $androidenterpriseService->entitlements;
 *  </code>
 */
class Entitlements extends \Google\Service\Resource
{
  /**
   * Removes an entitlement to an app for a user. **Note:** This item has been
   * deprecated. New integrations cannot use this method and can refer to our new
   * recommendations. (entitlements.delete)
   *
   * @param string $enterpriseId The ID of the enterprise.
   * @param string $userId The ID of the user.
   * @param string $entitlementId The ID of the entitlement (a product ID), e.g.
   * "app:com.google.android.gm".
   * @param array $optParams Optional parameters.
   * @throws \Google\Service\Exception
   */
  public function delete($enterpriseId, $userId, $entitlementId, $optParams = [])
  {
    $params = ['enterpriseId' => $enterpriseId, 'userId' => $userId, 'entitlementId' => $entitlementId];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params]);
  }
  /**
   * Retrieves details of an entitlement. **Note:** This item has been deprecated.
   * New integrations cannot use this method and can refer to our new
   * recommendations. (entitlements.get)
   *
   * @param string $enterpriseId The ID of the enterprise.
   * @param string $userId The ID of the user.
   * @param string $entitlementId The ID of the entitlement (a product ID), e.g.
   * "app:com.google.android.gm".
   * @param array $optParams Optional parameters.
   * @return Entitlement
   * @throws \Google\Service\Exception
   */
  public function get($enterpriseId, $userId, $entitlementId, $optParams = [])
  {
    $params = ['enterpriseId' => $enterpriseId, 'userId' => $userId, 'entitlementId' => $entitlementId];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Entitlement::class);
  }
  /**
   * Lists all entitlements for the specified user. Only the ID is set. **Note:**
   * This item has been deprecated. New integrations cannot use this method and
   * can refer to our new recommendations. (entitlements.listEntitlements)
   *
   * @param string $enterpriseId The ID of the enterprise.
   * @param string $userId The ID of the user.
   * @param array $optParams Optional parameters.
   * @return EntitlementsListResponse
   * @throws \Google\Service\Exception
   */
  public function listEntitlements($enterpriseId, $userId, $optParams = [])
  {
    $params = ['enterpriseId' => $enterpriseId, 'userId' => $userId];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], EntitlementsListResponse::class);
  }
  /**
   * Adds or updates an entitlement to an app for a user. **Note:** This item has
   * been deprecated. New integrations cannot use this method and can refer to our
   * new recommendations. (entitlements.update)
   *
   * @param string $enterpriseId The ID of the enterprise.
   * @param string $userId The ID of the user.
   * @param string $entitlementId The ID of the entitlement (a product ID), e.g.
   * "app:com.google.android.gm".
   * @param Entitlement $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool install Set to true to also install the product on all the
   * user's devices where possible. Failure to install on one or more devices will
   * not prevent this operation from returning successfully, as long as the
   * entitlement was successfully assigned to the user.
   * @return Entitlement
   * @throws \Google\Service\Exception
   */
  public function update($enterpriseId, $userId, $entitlementId, Entitlement $postBody, $optParams = [])
  {
    $params = ['enterpriseId' => $enterpriseId, 'userId' => $userId, 'entitlementId' => $entitlementId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('update', [$params], Entitlement::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Entitlements::class, 'Google_Service_AndroidEnterprise_Resource_Entitlements');
