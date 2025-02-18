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

namespace Google\Service\AnalyticsReporting\Resource;

use Google\Service\AnalyticsReporting\SearchUserActivityRequest;
use Google\Service\AnalyticsReporting\SearchUserActivityResponse;

/**
 * The "userActivity" collection of methods.
 * Typical usage is:
 *  <code>
 *   $analyticsreportingService = new Google\Service\AnalyticsReporting(...);
 *   $userActivity = $analyticsreportingService->userActivity;
 *  </code>
 */
class UserActivity extends \Google\Service\Resource
{
  /**
   * Returns User Activity data. (userActivity.search)
   *
   * @param SearchUserActivityRequest $postBody
   * @param array $optParams Optional parameters.
   * @return SearchUserActivityResponse
   * @throws \Google\Service\Exception
   */
  public function search(SearchUserActivityRequest $postBody, $optParams = [])
  {
    $params = ['postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('search', [$params], SearchUserActivityResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(UserActivity::class, 'Google_Service_AnalyticsReporting_Resource_UserActivity');
