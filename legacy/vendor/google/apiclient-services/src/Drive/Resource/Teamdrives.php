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

namespace Google\Service\Drive\Resource;

use Google\Service\Drive\TeamDrive;
use Google\Service\Drive\TeamDriveList;

/**
 * The "teamdrives" collection of methods.
 * Typical usage is:
 *  <code>
 *   $driveService = new Google\Service\Drive(...);
 *   $teamdrives = $driveService->teamdrives;
 *  </code>
 */
class Teamdrives extends \Google\Service\Resource
{
  /**
   * Deprecated: Use `drives.create` instead. (teamdrives.create)
   *
   * @param string $requestId Required. An ID, such as a random UUID, which
   * uniquely identifies this user's request for idempotent creation of a Team
   * Drive. A repeated request by the same user and with the same request ID will
   * avoid creating duplicates by attempting to create the same Team Drive. If the
   * Team Drive already exists a 409 error will be returned.
   * @param TeamDrive $postBody
   * @param array $optParams Optional parameters.
   * @return TeamDrive
   * @throws \Google\Service\Exception
   */
  public function create($requestId, TeamDrive $postBody, $optParams = [])
  {
    $params = ['requestId' => $requestId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], TeamDrive::class);
  }
  /**
   * Deprecated: Use `drives.delete` instead. (teamdrives.delete)
   *
   * @param string $teamDriveId The ID of the Team Drive
   * @param array $optParams Optional parameters.
   * @throws \Google\Service\Exception
   */
  public function delete($teamDriveId, $optParams = [])
  {
    $params = ['teamDriveId' => $teamDriveId];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params]);
  }
  /**
   * Deprecated: Use `drives.get` instead. (teamdrives.get)
   *
   * @param string $teamDriveId The ID of the Team Drive
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool useDomainAdminAccess Issue the request as a domain
   * administrator; if set to true, then the requester will be granted access if
   * they are an administrator of the domain to which the Team Drive belongs.
   * @return TeamDrive
   * @throws \Google\Service\Exception
   */
  public function get($teamDriveId, $optParams = [])
  {
    $params = ['teamDriveId' => $teamDriveId];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], TeamDrive::class);
  }
  /**
   * Deprecated: Use `drives.list` instead. (teamdrives.listTeamdrives)
   *
   * @param array $optParams Optional parameters.
   *
   * @opt_param int pageSize Maximum number of Team Drives to return.
   * @opt_param string pageToken Page token for Team Drives.
   * @opt_param string q Query string for searching Team Drives.
   * @opt_param bool useDomainAdminAccess Issue the request as a domain
   * administrator; if set to true, then all Team Drives of the domain in which
   * the requester is an administrator are returned.
   * @return TeamDriveList
   * @throws \Google\Service\Exception
   */
  public function listTeamdrives($optParams = [])
  {
    $params = [];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], TeamDriveList::class);
  }
  /**
   * Deprecated: Use `drives.update` instead. (teamdrives.update)
   *
   * @param string $teamDriveId The ID of the Team Drive
   * @param TeamDrive $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool useDomainAdminAccess Issue the request as a domain
   * administrator; if set to true, then the requester will be granted access if
   * they are an administrator of the domain to which the Team Drive belongs.
   * @return TeamDrive
   * @throws \Google\Service\Exception
   */
  public function update($teamDriveId, TeamDrive $postBody, $optParams = [])
  {
    $params = ['teamDriveId' => $teamDriveId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('update', [$params], TeamDrive::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Teamdrives::class, 'Google_Service_Drive_Resource_Teamdrives');
