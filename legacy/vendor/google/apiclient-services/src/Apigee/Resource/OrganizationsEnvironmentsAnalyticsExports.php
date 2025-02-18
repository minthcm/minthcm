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

use Google\Service\Apigee\GoogleCloudApigeeV1Export;
use Google\Service\Apigee\GoogleCloudApigeeV1ExportRequest;
use Google\Service\Apigee\GoogleCloudApigeeV1ListExportsResponse;

/**
 * The "exports" collection of methods.
 * Typical usage is:
 *  <code>
 *   $apigeeService = new Google\Service\Apigee(...);
 *   $exports = $apigeeService->organizations_environments_analytics_exports;
 *  </code>
 */
class OrganizationsEnvironmentsAnalyticsExports extends \Google\Service\Resource
{
  /**
   * Submit a data export job to be processed in the background. If the request is
   * successful, the API returns a 201 status, a URI that can be used to retrieve
   * the status of the export job, and the `state` value of "enqueued".
   * (exports.create)
   *
   * @param string $parent Required. Names of the parent organization and
   * environment. Must be of the form `organizations/{org}/environments/{env}`.
   * @param GoogleCloudApigeeV1ExportRequest $postBody
   * @param array $optParams Optional parameters.
   * @return GoogleCloudApigeeV1Export
   * @throws \Google\Service\Exception
   */
  public function create($parent, GoogleCloudApigeeV1ExportRequest $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], GoogleCloudApigeeV1Export::class);
  }
  /**
   * Gets the details and status of an analytics export job. If the export job is
   * still in progress, its `state` is set to "running". After the export job has
   * completed successfully, its `state` is set to "completed". If the export job
   * fails, its `state` is set to `failed`. (exports.get)
   *
   * @param string $name Required. Resource name of the export to get.
   * @param array $optParams Optional parameters.
   * @return GoogleCloudApigeeV1Export
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], GoogleCloudApigeeV1Export::class);
  }
  /**
   * Lists the details and status of all analytics export jobs belonging to the
   * parent organization and environment.
   * (exports.listOrganizationsEnvironmentsAnalyticsExports)
   *
   * @param string $parent Required. Names of the parent organization and
   * environment. Must be of the form `organizations/{org}/environments/{env}`.
   * @param array $optParams Optional parameters.
   * @return GoogleCloudApigeeV1ListExportsResponse
   * @throws \Google\Service\Exception
   */
  public function listOrganizationsEnvironmentsAnalyticsExports($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], GoogleCloudApigeeV1ListExportsResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(OrganizationsEnvironmentsAnalyticsExports::class, 'Google_Service_Apigee_Resource_OrganizationsEnvironmentsAnalyticsExports');
