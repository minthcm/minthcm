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

namespace Google\Service\CloudTasks\Resource;

use Google\Service\CloudTasks\CmekConfig;
use Google\Service\CloudTasks\ListLocationsResponse;
use Google\Service\CloudTasks\Location;

/**
 * The "locations" collection of methods.
 * Typical usage is:
 *  <code>
 *   $cloudtasksService = new Google\Service\CloudTasks(...);
 *   $locations = $cloudtasksService->projects_locations;
 *  </code>
 */
class ProjectsLocations extends \Google\Service\Resource
{
  /**
   * Gets information about a location. (locations.get)
   *
   * @param string $name Resource name for the location.
   * @param array $optParams Optional parameters.
   * @return Location
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Location::class);
  }
  /**
   * Gets the CMEK config. Gets the Customer Managed Encryption Key configured
   * with the Cloud Tasks lcoation. By default there is no kms_key configured.
   * (locations.getCmekConfig)
   *
   * @param string $name Required. The config. For example:
   * projects/PROJECT_ID/locations/LOCATION_ID/CmekConfig`
   * @param array $optParams Optional parameters.
   * @return CmekConfig
   * @throws \Google\Service\Exception
   */
  public function getCmekConfig($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('getCmekConfig', [$params], CmekConfig::class);
  }
  /**
   * Lists information about the supported locations for this service.
   * (locations.listProjectsLocations)
   *
   * @param string $name The resource that owns the locations collection, if
   * applicable.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter A filter to narrow down results to a preferred
   * subset. The filtering language accepts strings like `"displayName=tokyo"`,
   * and is documented in more detail in [AIP-160](https://google.aip.dev/160).
   * @opt_param int pageSize The maximum number of results to return. If not set,
   * the service selects a default.
   * @opt_param string pageToken A page token received from the `next_page_token`
   * field in the response. Send that page token to receive the subsequent page.
   * @return ListLocationsResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsLocations($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListLocationsResponse::class);
  }
  /**
   * Creates or Updates a CMEK config. Updates the Customer Managed Encryption Key
   * assotiated with the Cloud Tasks location (Creates if the key does not already
   * exist). All new tasks created in the location will be encrypted at-rest with
   * the KMS-key provided in the config. (locations.updateCmekConfig)
   *
   * @param string $name Output only. The config resource name which includes the
   * project and location and must end in 'cmekConfig', in the format
   * projects/PROJECT_ID/locations/LOCATION_ID/cmekConfig`
   * @param CmekConfig $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string updateMask List of fields to be updated in this request.
   * @return CmekConfig
   * @throws \Google\Service\Exception
   */
  public function updateCmekConfig($name, CmekConfig $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('updateCmekConfig', [$params], CmekConfig::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocations::class, 'Google_Service_CloudTasks_Resource_ProjectsLocations');
