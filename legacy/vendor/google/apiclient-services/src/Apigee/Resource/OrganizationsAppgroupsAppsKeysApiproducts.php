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

namespace Google\Service\Apigee\Resource;

use Google\Service\Apigee\GoogleCloudApigeeV1AppGroupAppKey;
use Google\Service\Apigee\GoogleProtobufEmpty;

/**
 * The "apiproducts" collection of methods.
 * Typical usage is:
 *  <code>
 *   $apigeeService = new Google\Service\Apigee(...);
 *   $apiproducts = $apigeeService->organizations_appgroups_apps_keys_apiproducts;
 *  </code>
 */
class OrganizationsAppgroupsAppsKeysApiproducts extends \Google\Service\Resource
{
  /**
   * Removes an API product from an app's consumer key. After the API product is
   * removed, the app cannot access the API resources defined in that API product.
   * **Note**: The consumer key is not removed, only its association with the API
   * product. (apiproducts.delete)
   *
   * @param string $name Required. Parent of the AppGroup app key. Use the
   * following structure in your request: `organizations/{org}/appgroups/{app_grou
   * p_name}/apps/{app}/keys/{key}/apiproducts/{apiproduct}`
   * @param array $optParams Optional parameters.
   * @return GoogleCloudApigeeV1AppGroupAppKey
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], GoogleCloudApigeeV1AppGroupAppKey::class);
  }
  /**
   * Approves or revokes the consumer key for an API product. After a consumer key
   * is approved, the app can use it to access APIs. A consumer key that is
   * revoked or pending cannot be used to access an API. Any access tokens
   * associated with a revoked consumer key will remain active. However, Apigee
   * checks the status of the consumer key and if set to `revoked` will not allow
   * access to the API. (apiproducts.updateAppGroupAppKeyApiProduct)
   *
   * @param string $name Required. Name of the API product in the developer app
   * key in the following format: `organizations/{org}/appgroups/{app_group_name}/
   * apps/{app}/keys/{key}/apiproducts/{apiproduct}`
   * @param array $optParams Optional parameters.
   *
   * @opt_param string action Approve or revoke the consumer key by setting this
   * value to `approve` or `revoke` respectively. The `Content-Type` header, if
   * set, must be set to `application/octet-stream`, with empty body.
   * @return GoogleProtobufEmpty
   * @throws \Google\Service\Exception
   */
  public function updateAppGroupAppKeyApiProduct($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('updateAppGroupAppKeyApiProduct', [$params], GoogleProtobufEmpty::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(OrganizationsAppgroupsAppsKeysApiproducts::class, 'Google_Service_Apigee_Resource_OrganizationsAppgroupsAppsKeysApiproducts');
