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

namespace Google\Service\ServiceDirectory\Resource;

use Google\Service\ServiceDirectory\Endpoint;
use Google\Service\ServiceDirectory\ListEndpointsResponse;
use Google\Service\ServiceDirectory\ServicedirectoryEmpty;

/**
 * The "endpoints" collection of methods.
 * Typical usage is:
 *  <code>
 *   $servicedirectoryService = new Google\Service\ServiceDirectory(...);
 *   $endpoints = $servicedirectoryService->projects_locations_namespaces_services_endpoints;
 *  </code>
 */
class ProjectsLocationsNamespacesServicesEndpoints extends \Google\Service\Resource
{
  /**
   * Creates an endpoint, and returns the new endpoint. (endpoints.create)
   *
   * @param string $parent Required. The resource name of the service that this
   * endpoint provides.
   * @param Endpoint $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string endpointId Required. The Resource ID must be 1-63
   * characters long, and comply with RFC1035. Specifically, the name must be 1-63
   * characters long and match the regular expression
   * `[a-z](?:[-a-z0-9]{0,61}[a-z0-9])?` which means the first character must be a
   * lowercase letter, and all following characters must be a dash, lowercase
   * letter, or digit, except the last character, which cannot be a dash.
   * @return Endpoint
   * @throws \Google\Service\Exception
   */
  public function create($parent, Endpoint $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], Endpoint::class);
  }
  /**
   * Deletes an endpoint. (endpoints.delete)
   *
   * @param string $name Required. The name of the endpoint to delete.
   * @param array $optParams Optional parameters.
   * @return ServicedirectoryEmpty
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], ServicedirectoryEmpty::class);
  }
  /**
   * Gets an endpoint. (endpoints.get)
   *
   * @param string $name Required. The name of the endpoint to get.
   * @param array $optParams Optional parameters.
   * @return Endpoint
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Endpoint::class);
  }
  /**
   * Lists all endpoints.
   * (endpoints.listProjectsLocationsNamespacesServicesEndpoints)
   *
   * @param string $parent Required. The resource name of the service whose
   * endpoints you'd like to list.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter Optional. The filter to list results by. General
   * `filter` string syntax: ` ()` * `` can be `name`, `address`, `port`, or
   * `annotations.` for map field * `` can be `<`, `>`, `<=`, `>=`, `!=`, `=`,
   * `:`. Of which `:` means `HAS`, and is roughly the same as `=` * `` must be
   * the same data type as field * `` can be `AND`, `OR`, `NOT` Examples of valid
   * filters: * `annotations.owner` returns endpoints that have a annotation with
   * the key `owner`, this is the same as `annotations:owner` *
   * `annotations.protocol=gRPC` returns endpoints that have key/value
   * `protocol=gRPC` * `address=192.108.1.105` returns endpoints that have this
   * address * `port>8080` returns endpoints that have port number larger than
   * 8080 * `name>projects/my-project/locations/us-east1/namespaces/my-
   * namespace/services/my-service/endpoints/endpoint-c` returns endpoints that
   * have name that is alphabetically later than the string, so "endpoint-e" is
   * returned but "endpoint-a" is not * `annotations.owner!=sd AND
   * annotations.foo=bar` returns endpoints that have `owner` in annotation key
   * but value is not `sd` AND have key/value `foo=bar` * `doesnotexist.foo=bar`
   * returns an empty list. Note that endpoint doesn't have a field called
   * "doesnotexist". Since the filter does not match any endpoints, it returns no
   * results For more information about filtering, see [API
   * Filtering](https://aip.dev/160).
   * @opt_param string orderBy Optional. The order to list results by. General
   * `order_by` string syntax: ` () (,)` * `` allows values: `name`, `address`,
   * `port` * `` ascending or descending order by ``. If this is left blank, `asc`
   * is used Note that an empty `order_by` string results in default order, which
   * is order by `name` in ascending order.
   * @opt_param int pageSize Optional. The maximum number of items to return.
   * @opt_param string pageToken Optional. The next_page_token value returned from
   * a previous List request, if any.
   * @return ListEndpointsResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsLocationsNamespacesServicesEndpoints($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListEndpointsResponse::class);
  }
  /**
   * Updates an endpoint. (endpoints.patch)
   *
   * @param string $name Immutable. The resource name for the endpoint in the
   * format `projects/locations/namespaces/services/endpoints`.
   * @param Endpoint $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string updateMask Required. List of fields to be updated in this
   * request.
   * @return Endpoint
   * @throws \Google\Service\Exception
   */
  public function patch($name, Endpoint $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], Endpoint::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsNamespacesServicesEndpoints::class, 'Google_Service_ServiceDirectory_Resource_ProjectsLocationsNamespacesServicesEndpoints');
