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

namespace Google\Service\SASPortalTesting\Resource;

use Google\Service\SASPortalTesting\SasPortalDeployment;
use Google\Service\SASPortalTesting\SasPortalListDeploymentsResponse;

/**
 * The "deployments" collection of methods.
 * Typical usage is:
 *  <code>
 *   $prod_tt_sasportalService = new Google\Service\SASPortalTesting(...);
 *   $deployments = $prod_tt_sasportalService->customers_nodes_deployments;
 *  </code>
 */
class CustomersNodesDeployments extends \Google\Service\Resource
{
  /**
   * Creates a new deployment. (deployments.create)
   *
   * @param string $parent Required. The parent resource name where the deployment
   * is to be created.
   * @param SasPortalDeployment $postBody
   * @param array $optParams Optional parameters.
   * @return SasPortalDeployment
   * @throws \Google\Service\Exception
   */
  public function create($parent, SasPortalDeployment $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], SasPortalDeployment::class);
  }
  /**
   * Lists deployments. (deployments.listCustomersNodesDeployments)
   *
   * @param string $parent Required. The parent resource name, for example,
   * "nodes/1", customer/1/nodes/2.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter The filter expression. The filter should have the
   * following format: "DIRECT_CHILDREN" or format: "direct_children". The filter
   * is case insensitive. If empty, then no deployments are filtered.
   * @opt_param int pageSize The maximum number of deployments to return in the
   * response.
   * @opt_param string pageToken A pagination token returned from a previous call
   * to ListDeployments that indicates where this listing should continue from.
   * @return SasPortalListDeploymentsResponse
   * @throws \Google\Service\Exception
   */
  public function listCustomersNodesDeployments($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], SasPortalListDeploymentsResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CustomersNodesDeployments::class, 'Google_Service_SASPortalTesting_Resource_CustomersNodesDeployments');
