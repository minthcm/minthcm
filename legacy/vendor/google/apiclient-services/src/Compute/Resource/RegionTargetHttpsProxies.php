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

namespace Google\Service\Compute\Resource;

use Google\Service\Compute\Operation;
use Google\Service\Compute\RegionTargetHttpsProxiesSetSslCertificatesRequest;
use Google\Service\Compute\TargetHttpsProxy;
use Google\Service\Compute\TargetHttpsProxyList;
use Google\Service\Compute\UrlMapReference;

/**
 * The "regionTargetHttpsProxies" collection of methods.
 * Typical usage is:
 *  <code>
 *   $computeService = new Google\Service\Compute(...);
 *   $regionTargetHttpsProxies = $computeService->regionTargetHttpsProxies;
 *  </code>
 */
class RegionTargetHttpsProxies extends \Google\Service\Resource
{
  /**
   * Deletes the specified TargetHttpsProxy resource.
   * (regionTargetHttpsProxies.delete)
   *
   * @param string $project Project ID for this request.
   * @param string $region Name of the region scoping this request.
   * @param string $targetHttpsProxy Name of the TargetHttpsProxy resource to
   * delete.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string requestId An optional request ID to identify requests.
   * Specify a unique request ID so that if you must retry your request, the
   * server will know to ignore the request if it has already been completed. For
   * example, consider a situation where you make an initial request and the
   * request times out. If you make the request again with the same request ID,
   * the server can check if original operation with the same request ID was
   * received, and if so, will ignore the second request. This prevents clients
   * from accidentally creating duplicate commitments. The request ID must be a
   * valid UUID with the exception that zero UUID is not supported (
   * 00000000-0000-0000-0000-000000000000).
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function delete($project, $region, $targetHttpsProxy, $optParams = [])
  {
    $params = ['project' => $project, 'region' => $region, 'targetHttpsProxy' => $targetHttpsProxy];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], Operation::class);
  }
  /**
   * Returns the specified TargetHttpsProxy resource in the specified region.
   * (regionTargetHttpsProxies.get)
   *
   * @param string $project Project ID for this request.
   * @param string $region Name of the region scoping this request.
   * @param string $targetHttpsProxy Name of the TargetHttpsProxy resource to
   * return.
   * @param array $optParams Optional parameters.
   * @return TargetHttpsProxy
   * @throws \Google\Service\Exception
   */
  public function get($project, $region, $targetHttpsProxy, $optParams = [])
  {
    $params = ['project' => $project, 'region' => $region, 'targetHttpsProxy' => $targetHttpsProxy];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], TargetHttpsProxy::class);
  }
  /**
   * Creates a TargetHttpsProxy resource in the specified project and region using
   * the data included in the request. (regionTargetHttpsProxies.insert)
   *
   * @param string $project Project ID for this request.
   * @param string $region Name of the region scoping this request.
   * @param TargetHttpsProxy $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string requestId An optional request ID to identify requests.
   * Specify a unique request ID so that if you must retry your request, the
   * server will know to ignore the request if it has already been completed. For
   * example, consider a situation where you make an initial request and the
   * request times out. If you make the request again with the same request ID,
   * the server can check if original operation with the same request ID was
   * received, and if so, will ignore the second request. This prevents clients
   * from accidentally creating duplicate commitments. The request ID must be a
   * valid UUID with the exception that zero UUID is not supported (
   * 00000000-0000-0000-0000-000000000000).
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function insert($project, $region, TargetHttpsProxy $postBody, $optParams = [])
  {
    $params = ['project' => $project, 'region' => $region, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('insert', [$params], Operation::class);
  }
  /**
   * Retrieves the list of TargetHttpsProxy resources available to the specified
   * project in the specified region.
   * (regionTargetHttpsProxies.listRegionTargetHttpsProxies)
   *
   * @param string $project Project ID for this request.
   * @param string $region Name of the region scoping this request.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter A filter expression that filters resources listed in
   * the response. Most Compute resources support two types of filter expressions:
   * expressions that support regular expressions and expressions that follow API
   * improvement proposal AIP-160. These two types of filter expressions cannot be
   * mixed in one request. If you want to use AIP-160, your expression must
   * specify the field name, an operator, and the value that you want to use for
   * filtering. The value must be a string, a number, or a boolean. The operator
   * must be either `=`, `!=`, `>`, `<`, `<=`, `>=` or `:`. For example, if you
   * are filtering Compute Engine instances, you can exclude instances named
   * `example-instance` by specifying `name != example-instance`. The `:*`
   * comparison can be used to test whether a key has been defined. For example,
   * to find all objects with `owner` label use: ``` labels.owner:* ``` You can
   * also filter nested fields. For example, you could specify
   * `scheduling.automaticRestart = false` to include instances only if they are
   * not scheduled for automatic restarts. You can use filtering on nested fields
   * to filter based on resource labels. To filter on multiple expressions,
   * provide each separate expression within parentheses. For example: ```
   * (scheduling.automaticRestart = true) (cpuPlatform = "Intel Skylake") ``` By
   * default, each expression is an `AND` expression. However, you can include
   * `AND` and `OR` expressions explicitly. For example: ``` (cpuPlatform = "Intel
   * Skylake") OR (cpuPlatform = "Intel Broadwell") AND
   * (scheduling.automaticRestart = true) ``` If you want to use a regular
   * expression, use the `eq` (equal) or `ne` (not equal) operator against a
   * single un-parenthesized expression with or without quotes or against multiple
   * parenthesized expressions. Examples: `fieldname eq unquoted literal`
   * `fieldname eq 'single quoted literal'` `fieldname eq "double quoted literal"`
   * `(fieldname1 eq literal) (fieldname2 ne "literal")` The literal value is
   * interpreted as a regular expression using Google RE2 library syntax. The
   * literal value must match the entire field. For example, to filter for
   * instances that do not end with name "instance", you would use `name ne
   * .*instance`. You cannot combine constraints on multiple fields using regular
   * expressions.
   * @opt_param string maxResults The maximum number of results per page that
   * should be returned. If the number of available results is larger than
   * `maxResults`, Compute Engine returns a `nextPageToken` that can be used to
   * get the next page of results in subsequent list requests. Acceptable values
   * are `0` to `500`, inclusive. (Default: `500`)
   * @opt_param string orderBy Sorts list results by a certain order. By default,
   * results are returned in alphanumerical order based on the resource name. You
   * can also sort results in descending order based on the creation timestamp
   * using `orderBy="creationTimestamp desc"`. This sorts results based on the
   * `creationTimestamp` field in reverse chronological order (newest result
   * first). Use this to sort resources like operations so that the newest
   * operation is returned first. Currently, only sorting by `name` or
   * `creationTimestamp desc` is supported.
   * @opt_param string pageToken Specifies a page token to use. Set `pageToken` to
   * the `nextPageToken` returned by a previous list request to get the next page
   * of results.
   * @opt_param bool returnPartialSuccess Opt-in for partial success behavior
   * which provides partial results in case of failure. The default value is
   * false. For example, when partial success behavior is enabled, aggregatedList
   * for a single zone scope either returns all resources in the zone or no
   * resources, with an error code.
   * @return TargetHttpsProxyList
   * @throws \Google\Service\Exception
   */
  public function listRegionTargetHttpsProxies($project, $region, $optParams = [])
  {
    $params = ['project' => $project, 'region' => $region];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], TargetHttpsProxyList::class);
  }
  /**
   * Patches the specified regional TargetHttpsProxy resource with the data
   * included in the request. This method supports PATCH semantics and uses JSON
   * merge patch format and processing rules. (regionTargetHttpsProxies.patch)
   *
   * @param string $project Project ID for this request.
   * @param string $region Name of the region for this request.
   * @param string $targetHttpsProxy Name of the TargetHttpsProxy resource to
   * patch.
   * @param TargetHttpsProxy $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string requestId An optional request ID to identify requests.
   * Specify a unique request ID so that if you must retry your request, the
   * server will know to ignore the request if it has already been completed. For
   * example, consider a situation where you make an initial request and the
   * request times out. If you make the request again with the same request ID,
   * the server can check if original operation with the same request ID was
   * received, and if so, will ignore the second request. This prevents clients
   * from accidentally creating duplicate commitments. The request ID must be a
   * valid UUID with the exception that zero UUID is not supported (
   * 00000000-0000-0000-0000-000000000000).
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function patch($project, $region, $targetHttpsProxy, TargetHttpsProxy $postBody, $optParams = [])
  {
    $params = ['project' => $project, 'region' => $region, 'targetHttpsProxy' => $targetHttpsProxy, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], Operation::class);
  }
  /**
   * Replaces SslCertificates for TargetHttpsProxy.
   * (regionTargetHttpsProxies.setSslCertificates)
   *
   * @param string $project Project ID for this request.
   * @param string $region Name of the region scoping this request.
   * @param string $targetHttpsProxy Name of the TargetHttpsProxy resource to set
   * an SslCertificates resource for.
   * @param RegionTargetHttpsProxiesSetSslCertificatesRequest $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string requestId An optional request ID to identify requests.
   * Specify a unique request ID so that if you must retry your request, the
   * server will know to ignore the request if it has already been completed. For
   * example, consider a situation where you make an initial request and the
   * request times out. If you make the request again with the same request ID,
   * the server can check if original operation with the same request ID was
   * received, and if so, will ignore the second request. This prevents clients
   * from accidentally creating duplicate commitments. The request ID must be a
   * valid UUID with the exception that zero UUID is not supported (
   * 00000000-0000-0000-0000-000000000000).
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function setSslCertificates($project, $region, $targetHttpsProxy, RegionTargetHttpsProxiesSetSslCertificatesRequest $postBody, $optParams = [])
  {
    $params = ['project' => $project, 'region' => $region, 'targetHttpsProxy' => $targetHttpsProxy, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('setSslCertificates', [$params], Operation::class);
  }
  /**
   * Changes the URL map for TargetHttpsProxy.
   * (regionTargetHttpsProxies.setUrlMap)
   *
   * @param string $project Project ID for this request.
   * @param string $region Name of the region scoping this request.
   * @param string $targetHttpsProxy Name of the TargetHttpsProxy to set a URL map
   * for.
   * @param UrlMapReference $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string requestId An optional request ID to identify requests.
   * Specify a unique request ID so that if you must retry your request, the
   * server will know to ignore the request if it has already been completed. For
   * example, consider a situation where you make an initial request and the
   * request times out. If you make the request again with the same request ID,
   * the server can check if original operation with the same request ID was
   * received, and if so, will ignore the second request. This prevents clients
   * from accidentally creating duplicate commitments. The request ID must be a
   * valid UUID with the exception that zero UUID is not supported (
   * 00000000-0000-0000-0000-000000000000).
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function setUrlMap($project, $region, $targetHttpsProxy, UrlMapReference $postBody, $optParams = [])
  {
    $params = ['project' => $project, 'region' => $region, 'targetHttpsProxy' => $targetHttpsProxy, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('setUrlMap', [$params], Operation::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(RegionTargetHttpsProxies::class, 'Google_Service_Compute_Resource_RegionTargetHttpsProxies');
