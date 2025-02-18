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

namespace Google\Service\Logging\Resource;

use Google\Service\Logging\ListLogsResponse;

/**
 * The "logs" collection of methods.
 * Typical usage is:
 *  <code>
 *   $loggingService = new Google\Service\Logging(...);
 *   $logs = $loggingService->projects_locations_buckets_views_logs;
 *  </code>
 */
class ProjectsLocationsBucketsViewsLogs extends \Google\Service\Resource
{
  /**
   * Lists the logs in projects, organizations, folders, or billing accounts. Only
   * logs that have entries are listed.
   * (logs.listProjectsLocationsBucketsViewsLogs)
   *
   * @param string $parent Required. The resource name to list logs for:
   * projects/[PROJECT_ID] organizations/[ORGANIZATION_ID]
   * billingAccounts/[BILLING_ACCOUNT_ID] folders/[FOLDER_ID]
   * @param array $optParams Optional parameters.
   *
   * @opt_param int pageSize Optional. The maximum number of results to return
   * from this request. Non-positive values are ignored. The presence of
   * nextPageToken in the response indicates that more results might be available.
   * @opt_param string pageToken Optional. If present, then retrieve the next
   * batch of results from the preceding call to this method. pageToken must be
   * the value of nextPageToken from the previous response. The values of other
   * method parameters should be identical to those in the previous call.
   * @opt_param string resourceNames Optional. List of resource names to list logs
   * for: projects/[PROJECT_ID]/locations/[LOCATION_ID]/buckets/[BUCKET_ID]/views/
   * [VIEW_ID] organizations/[ORGANIZATION_ID]/locations/[LOCATION_ID]/buckets/[BU
   * CKET_ID]/views/[VIEW_ID] billingAccounts/[BILLING_ACCOUNT_ID]/locations/[LOCA
   * TION_ID]/buckets/[BUCKET_ID]/views/[VIEW_ID] folders/[FOLDER_ID]/locations/[L
   * OCATION_ID]/buckets/[BUCKET_ID]/views/[VIEW_ID]To support legacy queries, it
   * could also be: projects/[PROJECT_ID] organizations/[ORGANIZATION_ID]
   * billingAccounts/[BILLING_ACCOUNT_ID] folders/[FOLDER_ID]The resource name in
   * the parent field is added to this list.
   * @return ListLogsResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsLocationsBucketsViewsLogs($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListLogsResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsBucketsViewsLogs::class, 'Google_Service_Logging_Resource_ProjectsLocationsBucketsViewsLogs');
