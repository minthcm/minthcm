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

namespace Google\Service\VMwareEngine\Resource;

use Google\Service\VMwareEngine\ListPeeringRoutesResponse;

/**
 * The "peeringRoutes" collection of methods.
 * Typical usage is:
 *  <code>
 *   $vmwareengineService = new Google\Service\VMwareEngine(...);
 *   $peeringRoutes = $vmwareengineService->projects_locations_global_networkPeerings_peeringRoutes;
 *  </code>
 */
class ProjectsLocationsVmwareengineGlobalNetworkPeeringsPeeringRoutes extends \Google\Service\Resource
{
  /**
   * Lists the network peering routes exchanged over a peering connection. (peerin
   * gRoutes.listProjectsLocationsVmwareengineGlobalNetworkPeeringsPeeringRoutes)
   *
   * @param string $parent Required. The resource name of the network peering to
   * retrieve peering routes from. Resource names are schemeless URIs that follow
   * the conventions in https://cloud.google.com/apis/design/resource_names. For
   * example: `projects/my-project/locations/global/networkPeerings/my-peering`
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter A filter expression that matches resources returned
   * in the response. Currently, only filtering on the `direction` field is
   * supported. To return routes imported from the peer network, provide
   * "direction=INCOMING". To return routes exported from the VMware Engine
   * network, provide "direction=OUTGOING". Other filter expressions return an
   * error.
   * @opt_param int pageSize The maximum number of peering routes to return in one
   * page. The service may return fewer than this value. The maximum value is
   * coerced to 1000. The default value of this field is 500.
   * @opt_param string pageToken A page token, received from a previous
   * `ListPeeringRoutes` call. Provide this to retrieve the subsequent page. When
   * paginating, all other parameters provided to `ListPeeringRoutes` must match
   * the call that provided the page token.
   * @return ListPeeringRoutesResponse
   */
  public function listProjectsLocationsVmwareengineGlobalNetworkPeeringsPeeringRoutes($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListPeeringRoutesResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsVmwareengineGlobalNetworkPeeringsPeeringRoutes::class, 'Google_Service_VMwareEngine_Resource_ProjectsLocationsVmwareengineGlobalNetworkPeeringsPeeringRoutes');
