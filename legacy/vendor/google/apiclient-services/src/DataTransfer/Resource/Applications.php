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

namespace Google\Service\DataTransfer\Resource;

use Google\Service\DataTransfer\Application;
use Google\Service\DataTransfer\ApplicationsListResponse;

/**
 * The "applications" collection of methods.
 * Typical usage is:
 *  <code>
 *   $adminService = new Google\Service\DataTransfer(...);
 *   $applications = $adminService->applications;
 *  </code>
 */
class Applications extends \Google\Service\Resource
{
  /**
   * Retrieves information about an application for the given application ID.
   * (applications.get)
   *
   * @param string $applicationId ID of the application resource to be retrieved.
   * @param array $optParams Optional parameters.
   * @return Application
   * @throws \Google\Service\Exception
   */
  public function get($applicationId, $optParams = [])
  {
    $params = ['applicationId' => $applicationId];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Application::class);
  }
  /**
   * Lists the applications available for data transfer for a customer.
   * (applications.listApplications)
   *
   * @param array $optParams Optional parameters.
   *
   * @opt_param string customerId Immutable ID of the Google Workspace account.
   * @opt_param int maxResults Maximum number of results to return. Default is
   * 100.
   * @opt_param string pageToken Token to specify next page in the list.
   * @return ApplicationsListResponse
   * @throws \Google\Service\Exception
   */
  public function listApplications($optParams = [])
  {
    $params = [];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ApplicationsListResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Applications::class, 'Google_Service_DataTransfer_Resource_Applications');
