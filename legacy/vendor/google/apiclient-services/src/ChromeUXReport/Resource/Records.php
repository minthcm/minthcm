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

namespace Google\Service\ChromeUXReport\Resource;

use Google\Service\ChromeUXReport\QueryHistoryRequest;
use Google\Service\ChromeUXReport\QueryHistoryResponse;
use Google\Service\ChromeUXReport\QueryRequest;
use Google\Service\ChromeUXReport\QueryResponse;

/**
 * The "records" collection of methods.
 * Typical usage is:
 *  <code>
 *   $chromeuxreportService = new Google\Service\ChromeUXReport(...);
 *   $records = $chromeuxreportService->records;
 *  </code>
 */
class Records extends \Google\Service\Resource
{
  /**
   * Queries the Chrome User Experience Report for a timeseries `history record`
   * for a given site. Returns a `history record` that contains one or more
   * `metric timeseries` corresponding to performance data about the requested
   * site. (records.queryHistoryRecord)
   *
   * @param QueryHistoryRequest $postBody
   * @param array $optParams Optional parameters.
   * @return QueryHistoryResponse
   * @throws \Google\Service\Exception
   */
  public function queryHistoryRecord(QueryHistoryRequest $postBody, $optParams = [])
  {
    $params = ['postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('queryHistoryRecord', [$params], QueryHistoryResponse::class);
  }
  /**
   * Queries the Chrome User Experience for a single `record` for a given site.
   * Returns a `record` that contains one or more `metrics` corresponding to
   * performance data about the requested site. (records.queryRecord)
   *
   * @param QueryRequest $postBody
   * @param array $optParams Optional parameters.
   * @return QueryResponse
   * @throws \Google\Service\Exception
   */
  public function queryRecord(QueryRequest $postBody, $optParams = [])
  {
    $params = ['postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('queryRecord', [$params], QueryResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Records::class, 'Google_Service_ChromeUXReport_Resource_Records');
