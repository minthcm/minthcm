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

namespace Google\Service\YouTubeReporting\Resource;

use Google\Service\YouTubeReporting\ListReportTypesResponse;

/**
 * The "reportTypes" collection of methods.
 * Typical usage is:
 *  <code>
 *   $youtubereportingService = new Google\Service\YouTubeReporting(...);
 *   $reportTypes = $youtubereportingService->reportTypes;
 *  </code>
 */
class ReportTypes extends \Google\Service\Resource
{
  /**
   * Lists report types. (reportTypes.listReportTypes)
   *
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool includeSystemManaged If set to true, also system-managed
   * report types will be returned; otherwise only the report types that can be
   * used to create new reporting jobs will be returned.
   * @opt_param string onBehalfOfContentOwner The content owner's external ID on
   * which behalf the user is acting on. If not set, the user is acting for
   * himself (his own channel).
   * @opt_param int pageSize Requested page size. Server may return fewer report
   * types than requested. If unspecified, server will pick an appropriate
   * default.
   * @opt_param string pageToken A token identifying a page of results the server
   * should return. Typically, this is the value of
   * ListReportTypesResponse.next_page_token returned in response to the previous
   * call to the `ListReportTypes` method.
   * @return ListReportTypesResponse
   * @throws \Google\Service\Exception
   */
  public function listReportTypes($optParams = [])
  {
    $params = [];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListReportTypesResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ReportTypes::class, 'Google_Service_YouTubeReporting_Resource_ReportTypes');
