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

namespace Google\Service\GamesConfiguration\Resource;

use Google\Service\GamesConfiguration\LeaderboardConfiguration;
use Google\Service\GamesConfiguration\LeaderboardConfigurationListResponse;

/**
 * The "leaderboardConfigurations" collection of methods.
 * Typical usage is:
 *  <code>
 *   $gamesConfigurationService = new Google\Service\GamesConfiguration(...);
 *   $leaderboardConfigurations = $gamesConfigurationService->leaderboardConfigurations;
 *  </code>
 */
class LeaderboardConfigurations extends \Google\Service\Resource
{
  /**
   * Delete the leaderboard configuration with the given ID.
   * (leaderboardConfigurations.delete)
   *
   * @param string $leaderboardId The ID of the leaderboard.
   * @param array $optParams Optional parameters.
   * @throws \Google\Service\Exception
   */
  public function delete($leaderboardId, $optParams = [])
  {
    $params = ['leaderboardId' => $leaderboardId];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params]);
  }
  /**
   * Retrieves the metadata of the leaderboard configuration with the given ID.
   * (leaderboardConfigurations.get)
   *
   * @param string $leaderboardId The ID of the leaderboard.
   * @param array $optParams Optional parameters.
   * @return LeaderboardConfiguration
   * @throws \Google\Service\Exception
   */
  public function get($leaderboardId, $optParams = [])
  {
    $params = ['leaderboardId' => $leaderboardId];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], LeaderboardConfiguration::class);
  }
  /**
   * Insert a new leaderboard configuration in this application.
   * (leaderboardConfigurations.insert)
   *
   * @param string $applicationId The application ID from the Google Play
   * developer console.
   * @param LeaderboardConfiguration $postBody
   * @param array $optParams Optional parameters.
   * @return LeaderboardConfiguration
   * @throws \Google\Service\Exception
   */
  public function insert($applicationId, LeaderboardConfiguration $postBody, $optParams = [])
  {
    $params = ['applicationId' => $applicationId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('insert', [$params], LeaderboardConfiguration::class);
  }
  /**
   * Returns a list of the leaderboard configurations in this application.
   * (leaderboardConfigurations.listLeaderboardConfigurations)
   *
   * @param string $applicationId The application ID from the Google Play
   * developer console.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int maxResults The maximum number of resource configurations to
   * return in the response, used for paging. For any response, the actual number
   * of resources returned may be less than the specified `maxResults`.
   * @opt_param string pageToken The token returned by the previous request.
   * @return LeaderboardConfigurationListResponse
   * @throws \Google\Service\Exception
   */
  public function listLeaderboardConfigurations($applicationId, $optParams = [])
  {
    $params = ['applicationId' => $applicationId];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], LeaderboardConfigurationListResponse::class);
  }
  /**
   * Update the metadata of the leaderboard configuration with the given ID.
   * (leaderboardConfigurations.update)
   *
   * @param string $leaderboardId The ID of the leaderboard.
   * @param LeaderboardConfiguration $postBody
   * @param array $optParams Optional parameters.
   * @return LeaderboardConfiguration
   * @throws \Google\Service\Exception
   */
  public function update($leaderboardId, LeaderboardConfiguration $postBody, $optParams = [])
  {
    $params = ['leaderboardId' => $leaderboardId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('update', [$params], LeaderboardConfiguration::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(LeaderboardConfigurations::class, 'Google_Service_GamesConfiguration_Resource_LeaderboardConfigurations');
