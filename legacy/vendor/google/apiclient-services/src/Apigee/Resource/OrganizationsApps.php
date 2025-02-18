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

use Google\Service\Apigee\GoogleCloudApigeeV1App;
use Google\Service\Apigee\GoogleCloudApigeeV1ListAppsResponse;

/**
 * The "apps" collection of methods.
 * Typical usage is:
 *  <code>
 *   $apigeeService = new Google\Service\Apigee(...);
 *   $apps = $apigeeService->organizations_apps;
 *  </code>
 */
class OrganizationsApps extends \Google\Service\Resource
{
  /**
   * Gets the app profile for the specified app ID. (apps.get)
   *
   * @param string $name Required. App ID in the following format:
   * `organizations/{org}/apps/{app}`
   * @param array $optParams Optional parameters.
   * @return GoogleCloudApigeeV1App
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], GoogleCloudApigeeV1App::class);
  }
  /**
   * Lists IDs of apps within an organization that have the specified app status
   * (approved or revoked) or are of the specified app type (developer or
   * company). (apps.listOrganizationsApps)
   *
   * @param string $parent Required. Resource path of the parent in the following
   * format: `organizations/{org}`
   * @param array $optParams Optional parameters.
   *
   * @opt_param string apiProduct API product.
   * @opt_param string apptype Optional. 'apptype' is no longer available. Use a
   * 'filter' instead.
   * @opt_param bool expand Optional. Flag that specifies whether to return an
   * expanded list of apps for the organization. Defaults to `false`.
   * @opt_param string filter Optional. The filter expression to be used to get
   * the list of apps, where filtering can be done on developerEmail, apiProduct,
   * consumerKey, status, appId, appName, appType and appGroup. Examples:
   * "developerEmail=foo@bar.com", "appType=AppGroup", or "appType=Developer"
   * "filter" is supported from ver 1.10.0 and above.
   * @opt_param string ids Optional. Comma-separated list of app IDs on which to
   * filter.
   * @opt_param bool includeCred Optional. Flag that specifies whether to include
   * credentials in the response.
   * @opt_param string keyStatus Optional. Key status of the app. Valid values
   * include `approved` or `revoked`. Defaults to `approved`.
   * @opt_param int pageSize Optional. Count of apps a single page can have in the
   * response. If unspecified, at most 100 apps will be returned. The maximum
   * value is 100; values above 100 will be coerced to 100. "page_size" is
   * supported from ver 1.10.0 and above.
   * @opt_param string pageToken Optional. The starting index record for listing
   * the developers. "page_token" is supported from ver 1.10.0 and above.
   * @opt_param string rows Optional. Maximum number of app IDs to return.
   * Defaults to 10000.
   * @opt_param string startKey Returns the list of apps starting from the
   * specified app ID.
   * @opt_param string status Optional. Filter by the status of the app. Valid
   * values are `approved` or `revoked`. Defaults to `approved`.
   * @return GoogleCloudApigeeV1ListAppsResponse
   * @throws \Google\Service\Exception
   */
  public function listOrganizationsApps($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], GoogleCloudApigeeV1ListAppsResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(OrganizationsApps::class, 'Google_Service_Apigee_Resource_OrganizationsApps');
