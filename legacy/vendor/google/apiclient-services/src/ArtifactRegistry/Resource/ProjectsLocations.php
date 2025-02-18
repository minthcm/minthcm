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

namespace Google\Service\ArtifactRegistry\Resource;

use Google\Service\ArtifactRegistry\ListLocationsResponse;
use Google\Service\ArtifactRegistry\Location;
use Google\Service\ArtifactRegistry\VPCSCConfig;

/**
 * The "locations" collection of methods.
 * Typical usage is:
 *  <code>
 *   $artifactregistryService = new Google\Service\ArtifactRegistry(...);
 *   $locations = $artifactregistryService->projects_locations;
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
   * Retrieves the VPCSC Config for the Project. (locations.getVpcscConfig)
   *
   * @param string $name Required. The name of the VPCSCConfig resource.
   * @param array $optParams Optional parameters.
   * @return VPCSCConfig
   * @throws \Google\Service\Exception
   */
  public function getVpcscConfig($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('getVpcscConfig', [$params], VPCSCConfig::class);
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
   * Updates the VPCSC Config for the Project. (locations.updateVpcscConfig)
   *
   * @param string $name The name of the project's VPC SC Config. Always of the
   * form: projects/{projectID}/locations/{location}/vpcscConfig In update
   * request: never set In response: always set
   * @param VPCSCConfig $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string updateMask Field mask to support partial updates.
   * @return VPCSCConfig
   * @throws \Google\Service\Exception
   */
  public function updateVpcscConfig($name, VPCSCConfig $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('updateVpcscConfig', [$params], VPCSCConfig::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocations::class, 'Google_Service_ArtifactRegistry_Resource_ProjectsLocations');
