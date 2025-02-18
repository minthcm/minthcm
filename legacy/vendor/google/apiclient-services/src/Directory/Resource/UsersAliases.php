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

namespace Google\Service\Directory\Resource;

use Google\Service\Directory\Alias;
use Google\Service\Directory\Aliases;
use Google\Service\Directory\Channel;

/**
 * The "aliases" collection of methods.
 * Typical usage is:
 *  <code>
 *   $adminService = new Google\Service\Directory(...);
 *   $aliases = $adminService->users_aliases;
 *  </code>
 */
class UsersAliases extends \Google\Service\Resource
{
  /**
   * Removes an alias. (aliases.delete)
   *
   * @param string $userKey Identifies the user in the API request. The value can
   * be the user's primary email address, alias email address, or unique user ID.
   * @param string $alias The alias to be removed.
   * @param array $optParams Optional parameters.
   * @throws \Google\Service\Exception
   */
  public function delete($userKey, $alias, $optParams = [])
  {
    $params = ['userKey' => $userKey, 'alias' => $alias];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params]);
  }
  /**
   * Adds an alias. (aliases.insert)
   *
   * @param string $userKey Identifies the user in the API request. The value can
   * be the user's primary email address, alias email address, or unique user ID.
   * @param Alias $postBody
   * @param array $optParams Optional parameters.
   * @return Alias
   * @throws \Google\Service\Exception
   */
  public function insert($userKey, Alias $postBody, $optParams = [])
  {
    $params = ['userKey' => $userKey, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('insert', [$params], Alias::class);
  }
  /**
   * Lists all aliases for a user. (aliases.listUsersAliases)
   *
   * @param string $userKey Identifies the user in the API request. The value can
   * be the user's primary email address, alias email address, or unique user ID.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string event Events to watch for.
   * @return Aliases
   * @throws \Google\Service\Exception
   */
  public function listUsersAliases($userKey, $optParams = [])
  {
    $params = ['userKey' => $userKey];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], Aliases::class);
  }
  /**
   * Watches for changes in users list. (aliases.watch)
   *
   * @param string $userKey Email or immutable ID of the user
   * @param Channel $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string event Events to watch for.
   * @return Channel
   * @throws \Google\Service\Exception
   */
  public function watch($userKey, Channel $postBody, $optParams = [])
  {
    $params = ['userKey' => $userKey, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('watch', [$params], Channel::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(UsersAliases::class, 'Google_Service_Directory_Resource_UsersAliases');
