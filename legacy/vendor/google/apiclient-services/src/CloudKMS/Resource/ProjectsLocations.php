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

namespace Google\Service\CloudKMS\Resource;

use Google\Service\CloudKMS\EkmConfig;
use Google\Service\CloudKMS\GenerateRandomBytesRequest;
use Google\Service\CloudKMS\GenerateRandomBytesResponse;
use Google\Service\CloudKMS\ListLocationsResponse;
use Google\Service\CloudKMS\Location;

/**
 * The "locations" collection of methods.
 * Typical usage is:
 *  <code>
 *   $cloudkmsService = new Google\Service\CloudKMS(...);
 *   $locations = $cloudkmsService->projects_locations;
 *  </code>
 */
class ProjectsLocations extends \Google\Service\Resource
{
  /**
   * Generate random bytes using the Cloud KMS randomness source in the provided
   * location. (locations.generateRandomBytes)
   *
   * @param string $location The project-specific location in which to generate
   * random bytes. For example, "projects/my-project/locations/us-central1".
   * @param GenerateRandomBytesRequest $postBody
   * @param array $optParams Optional parameters.
   * @return GenerateRandomBytesResponse
   * @throws \Google\Service\Exception
   */
  public function generateRandomBytes($location, GenerateRandomBytesRequest $postBody, $optParams = [])
  {
    $params = ['location' => $location, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('generateRandomBytes', [$params], GenerateRandomBytesResponse::class);
  }
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
   * Returns the EkmConfig singleton resource for a given project and location.
   * (locations.getEkmConfig)
   *
   * @param string $name Required. The name of the EkmConfig to get.
   * @param array $optParams Optional parameters.
   * @return EkmConfig
   * @throws \Google\Service\Exception
   */
  public function getEkmConfig($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('getEkmConfig', [$params], EkmConfig::class);
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
   * Updates the EkmConfig singleton resource for a given project and location.
   * (locations.updateEkmConfig)
   *
   * @param string $name Output only. The resource name for the EkmConfig in the
   * format `projects/locations/ekmConfig`.
   * @param EkmConfig $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string updateMask Required. List of fields to be updated in this
   * request.
   * @return EkmConfig
   * @throws \Google\Service\Exception
   */
  public function updateEkmConfig($name, EkmConfig $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('updateEkmConfig', [$params], EkmConfig::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocations::class, 'Google_Service_CloudKMS_Resource_ProjectsLocations');
