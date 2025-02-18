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

namespace Google\Service\YouTube\Resource;

use Google\Service\YouTube\MemberListResponse;

/**
 * The "members" collection of methods.
 * Typical usage is:
 *  <code>
 *   $youtubeService = new Google\Service\YouTube(...);
 *   $members = $youtubeService->members;
 *  </code>
 */
class Members extends \Google\Service\Resource
{
  /**
   * Retrieves a list of members that match the request criteria for a channel.
   * (members.listMembers)
   *
   * @param string|array $part The *part* parameter specifies the member resource
   * parts that the API response will include. Set the parameter value to snippet.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filterByMemberChannelId Comma separated list of channel
   * IDs. Only data about members that are part of this list will be included in
   * the response.
   * @opt_param string hasAccessToLevel Filter members in the results set to the
   * ones that have access to a level.
   * @opt_param string maxResults The *maxResults* parameter specifies the maximum
   * number of items that should be returned in the result set.
   * @opt_param string mode Parameter that specifies which channel members to
   * return.
   * @opt_param string pageToken The *pageToken* parameter identifies a specific
   * page in the result set that should be returned. In an API response, the
   * nextPageToken and prevPageToken properties identify other pages that could be
   * retrieved.
   * @return MemberListResponse
   * @throws \Google\Service\Exception
   */
  public function listMembers($part, $optParams = [])
  {
    $params = ['part' => $part];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], MemberListResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Members::class, 'Google_Service_YouTube_Resource_Members');
