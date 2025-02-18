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

use Google\Service\Apigee\GoogleApiHttpBody;
use Google\Service\Apigee\GoogleCloudApigeeV1AsyncQuery;
use Google\Service\Apigee\GoogleCloudApigeeV1AsyncQueryResultView;
use Google\Service\Apigee\GoogleCloudApigeeV1ListAsyncQueriesResponse;
use Google\Service\Apigee\GoogleCloudApigeeV1Query;

/**
 * The "hostQueries" collection of methods.
 * Typical usage is:
 *  <code>
 *   $apigeeService = new Google\Service\Apigee(...);
 *   $hostQueries = $apigeeService->organizations_hostQueries;
 *  </code>
 */
class OrganizationsHostQueries extends \Google\Service\Resource
{
  /**
   * Submit a query at host level to be processed in the background. If the
   * submission of the query succeeds, the API returns a 201 status and an ID that
   * refer to the query. In addition to the HTTP status 201, the `state` of
   * "enqueued" means that the request succeeded. (hostQueries.create)
   *
   * @param string $parent Required. The parent resource name. Must be of the form
   * `organizations/{org}`.
   * @param GoogleCloudApigeeV1Query $postBody
   * @param array $optParams Optional parameters.
   * @return GoogleCloudApigeeV1AsyncQuery
   * @throws \Google\Service\Exception
   */
  public function create($parent, GoogleCloudApigeeV1Query $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], GoogleCloudApigeeV1AsyncQuery::class);
  }
  /**
   * Get status of a query submitted at host level. If the query is still in
   * progress, the `state` is set to "running" After the query has completed
   * successfully, `state` is set to "completed" (hostQueries.get)
   *
   * @param string $name Required. Name of the asynchronous query to get. Must be
   * of the form `organizations/{org}/queries/{queryId}`.
   * @param array $optParams Optional parameters.
   * @return GoogleCloudApigeeV1AsyncQuery
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], GoogleCloudApigeeV1AsyncQuery::class);
  }
  /**
   * After the query is completed, use this API to retrieve the results. If the
   * request succeeds, and there is a non-zero result set, the result is
   * downloaded to the client as a zipped JSON file. The name of the downloaded
   * file will be: OfflineQueryResult-.zip Example:
   * `OfflineQueryResult-9cfc0d85-0f30-46d6-ae6f-318d0cb961bd.zip`
   * (hostQueries.getResult)
   *
   * @param string $name Required. Name of the asynchronous query result to get.
   * Must be of the form `organizations/{org}/queries/{queryId}/result`.
   * @param array $optParams Optional parameters.
   * @return GoogleApiHttpBody
   * @throws \Google\Service\Exception
   */
  public function getResult($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('getResult', [$params], GoogleApiHttpBody::class);
  }
  /**
   * (hostQueries.getResultView)
   *
   * @param string $name Required. Name of the asynchronous query result view to
   * get. Must be of the form `organizations/{org}/queries/{queryId}/resultView`.
   * @param array $optParams Optional parameters.
   * @return GoogleCloudApigeeV1AsyncQueryResultView
   * @throws \Google\Service\Exception
   */
  public function getResultView($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('getResultView', [$params], GoogleCloudApigeeV1AsyncQueryResultView::class);
  }
  /**
   * Return a list of Asynchronous Queries at host level.
   * (hostQueries.listOrganizationsHostQueries)
   *
   * @param string $parent Required. The parent resource name. Must be of the form
   * `organizations/{org}`.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string dataset Filter response list by dataset. Example: `api`,
   * `mint`
   * @opt_param string envgroupHostname Required. Filter response list by
   * hostname.
   * @opt_param string from Filter response list by returning asynchronous queries
   * that created after this date time. Time must be in ISO date-time format like
   * '2011-12-03T10:15:30Z'.
   * @opt_param string inclQueriesWithoutReport Flag to include asynchronous
   * queries that don't have a report denifition.
   * @opt_param string status Filter response list by asynchronous query status.
   * @opt_param string submittedBy Filter response list by user who submitted
   * queries.
   * @opt_param string to Filter response list by returning asynchronous queries
   * that created before this date time. Time must be in ISO date-time format like
   * '2011-12-03T10:16:30Z'.
   * @return GoogleCloudApigeeV1ListAsyncQueriesResponse
   * @throws \Google\Service\Exception
   */
  public function listOrganizationsHostQueries($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], GoogleCloudApigeeV1ListAsyncQueriesResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(OrganizationsHostQueries::class, 'Google_Service_Apigee_Resource_OrganizationsHostQueries');
