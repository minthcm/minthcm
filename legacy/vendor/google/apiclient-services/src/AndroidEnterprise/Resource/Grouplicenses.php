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

use Google\Service\AndroidEnterprise\GroupLicense;
use Google\Service\AndroidEnterprise\GroupLicensesListResponse;

/**
 * The "grouplicenses" collection of methods.
 * Typical usage is:
 *  <code>
 *   $androidenterpriseService = new Google\Service\AndroidEnterprise(...);
 *   $grouplicenses = $androidenterpriseService->grouplicenses;
 *  </code>
 */
class Grouplicenses extends \Google\Service\Resource
{
  /**
   * Retrieves details of an enterprise's group license for a product. **Note:**
   * This item has been deprecated. New integrations cannot use this method and
   * can refer to our new recommendations. (grouplicenses.get)
   *
   * @param string $enterpriseId The ID of the enterprise.
   * @param string $groupLicenseId The ID of the product the group license is for,
   * e.g. "app:com.google.android.gm".
   * @param array $optParams Optional parameters.
   * @return GroupLicense
   * @throws \Google\Service\Exception
   */
  public function get($enterpriseId, $groupLicenseId, $optParams = [])
  {
    $params = ['enterpriseId' => $enterpriseId, 'groupLicenseId' => $groupLicenseId];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], GroupLicense::class);
  }
  /**
   * Retrieves IDs of all products for which the enterprise has a group license.
   * **Note:** This item has been deprecated. New integrations cannot use this
   * method and can refer to our new recommendations.
   * (grouplicenses.listGrouplicenses)
   *
   * @param string $enterpriseId The ID of the enterprise.
   * @param array $optParams Optional parameters.
   * @return GroupLicensesListResponse
   * @throws \Google\Service\Exception
   */
  public function listGrouplicenses($enterpriseId, $optParams = [])
  {
    $params = ['enterpriseId' => $enterpriseId];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], GroupLicensesListResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Grouplicenses::class, 'Google_Service_AndroidEnterprise_Resource_Grouplicenses');
