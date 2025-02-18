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

use Google\Service\Apigee\GoogleCloudApigeeV1DeveloperAppKey;

/**
 * The "keys" collection of methods.
 * Typical usage is:
 *  <code>
 *   $apigeeService = new Google\Service\Apigee(...);
 *   $keys = $apigeeService->organizations_developers_apps_keys;
 *  </code>
 */
class OrganizationsDevelopersAppsKeys extends \Google\Service\Resource
{
  /**
   * Creates a custom consumer key and secret for a developer app. This is
   * particularly useful if you want to migrate existing consumer keys and secrets
   * to Apigee from another system. Consumer keys and secrets can contain letters,
   * numbers, underscores, and hyphens. No other special characters are allowed.
   * To avoid service disruptions, a consumer key and secret should not exceed 2
   * KBs each. **Note**: When creating the consumer key and secret, an association
   * to API products will not be made. Therefore, you should not specify the
   * associated API products in your request. Instead, use the
   * UpdateDeveloperAppKey API to make the association after the consumer key and
   * secret are created. If a consumer key and secret already exist, you can keep
   * them or delete them using the DeleteDeveloperAppKey API. **Note**: All keys
   * start out with status=approved, even if status=revoked is passed when the key
   * is created. To revoke a key, use the UpdateDeveloperAppKey API. (keys.create)
   *
   * @param string $parent Parent of the developer app key. Use the following
   * structure in your request:
   * 'organizations/{org}/developers/{developerEmail}/apps/{appName}'
   * @param GoogleCloudApigeeV1DeveloperAppKey $postBody
   * @param array $optParams Optional parameters.
   * @return GoogleCloudApigeeV1DeveloperAppKey
   * @throws \Google\Service\Exception
   */
  public function create($parent, GoogleCloudApigeeV1DeveloperAppKey $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], GoogleCloudApigeeV1DeveloperAppKey::class);
  }
  /**
   * Deletes an app's consumer key and removes all API products associated with
   * the app. After the consumer key is deleted, it cannot be used to access any
   * APIs. **Note**: After you delete a consumer key, you may want to: 1. Create a
   * new consumer key and secret for the developer app using the
   * CreateDeveloperAppKey API, and subsequently add an API product to the key
   * using the UpdateDeveloperAppKey API. 2. Delete the developer app, if it is no
   * longer required. (keys.delete)
   *
   * @param string $name Name of the developer app key. Use the following
   * structure in your request:
   * `organizations/{org}/developers/{developer_email}/apps/{app}/keys/{key}`
   * @param array $optParams Optional parameters.
   * @return GoogleCloudApigeeV1DeveloperAppKey
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], GoogleCloudApigeeV1DeveloperAppKey::class);
  }
  /**
   * Gets details for a consumer key for a developer app, including the key and
   * secret value, associated API products, and other information. (keys.get)
   *
   * @param string $name Name of the developer app key. Use the following
   * structure in your request:
   * `organizations/{org}/developers/{developer_email}/apps/{app}/keys/{key}`
   * @param array $optParams Optional parameters.
   * @return GoogleCloudApigeeV1DeveloperAppKey
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], GoogleCloudApigeeV1DeveloperAppKey::class);
  }
  /**
   * Updates the scope of an app. This API replaces the existing scopes with those
   * specified in the request. Include or exclude any existing scopes that you
   * want to retain or delete, respectively. The specified scopes must already be
   * defined for the API products associated with the app. This API sets the
   * `scopes` element under the `apiProducts` element in the attributes of the
   * app. (keys.replaceDeveloperAppKey)
   *
   * @param string $name Name of the developer app key. Use the following
   * structure in your request:
   * `organizations/{org}/developers/{developer_email}/apps/{app}/keys/{key}`
   * @param GoogleCloudApigeeV1DeveloperAppKey $postBody
   * @param array $optParams Optional parameters.
   * @return GoogleCloudApigeeV1DeveloperAppKey
   * @throws \Google\Service\Exception
   */
  public function replaceDeveloperAppKey($name, GoogleCloudApigeeV1DeveloperAppKey $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('replaceDeveloperAppKey', [$params], GoogleCloudApigeeV1DeveloperAppKey::class);
  }
  /**
   * Adds an API product to a developer app key, enabling the app that holds the
   * key to access the API resources bundled in the API product. In addition, you
   * can add attributes to a developer app key. This API replaces the existing
   * attributes with those specified in the request. Include or exclude any
   * existing attributes that you want to retain or delete, respectively. You can
   * use the same key to access all API products associated with the app.
   * (keys.updateDeveloperAppKey)
   *
   * @param string $name Name of the developer app key. Use the following
   * structure in your request:
   * `organizations/{org}/developers/{developer_email}/apps/{app}/keys/{key}`
   * @param GoogleCloudApigeeV1DeveloperAppKey $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string action Approve or revoke the consumer key by setting this
   * value to `approve` or `revoke`, respectively. The `Content-Type` header must
   * be set to `application/octet-stream`.
   * @return GoogleCloudApigeeV1DeveloperAppKey
   * @throws \Google\Service\Exception
   */
  public function updateDeveloperAppKey($name, GoogleCloudApigeeV1DeveloperAppKey $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('updateDeveloperAppKey', [$params], GoogleCloudApigeeV1DeveloperAppKey::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(OrganizationsDevelopersAppsKeys::class, 'Google_Service_Apigee_Resource_OrganizationsDevelopersAppsKeys');
