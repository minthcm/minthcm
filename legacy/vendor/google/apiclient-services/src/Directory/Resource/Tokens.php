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

use Google\Service\Directory\Token;
use Google\Service\Directory\Tokens as TokensModel;

/**
 * The "tokens" collection of methods.
 * Typical usage is:
 *  <code>
 *   $adminService = new Google\Service\Directory(...);
 *   $tokens = $adminService->tokens;
 *  </code>
 */
class Tokens extends \Google\Service\Resource
{
  /**
   * Deletes all access tokens issued by a user for an application.
   * (tokens.delete)
   *
   * @param string $userKey Identifies the user in the API request. The value can
   * be the user's primary email address, alias email address, or unique user ID.
   * @param string $clientId The Client ID of the application the token is issued
   * to.
   * @param array $optParams Optional parameters.
   * @throws \Google\Service\Exception
   */
  public function delete($userKey, $clientId, $optParams = [])
  {
    $params = ['userKey' => $userKey, 'clientId' => $clientId];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params]);
  }
  /**
   * Gets information about an access token issued by a user. (tokens.get)
   *
   * @param string $userKey Identifies the user in the API request. The value can
   * be the user's primary email address, alias email address, or unique user ID.
   * @param string $clientId The Client ID of the application the token is issued
   * to.
   * @param array $optParams Optional parameters.
   * @return Token
   * @throws \Google\Service\Exception
   */
  public function get($userKey, $clientId, $optParams = [])
  {
    $params = ['userKey' => $userKey, 'clientId' => $clientId];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Token::class);
  }
  /**
   * Returns the set of tokens specified user has issued to 3rd party
   * applications. (tokens.listTokens)
   *
   * @param string $userKey Identifies the user in the API request. The value can
   * be the user's primary email address, alias email address, or unique user ID.
   * @param array $optParams Optional parameters.
   * @return TokensModel
   * @throws \Google\Service\Exception
   */
  public function listTokens($userKey, $optParams = [])
  {
    $params = ['userKey' => $userKey];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], TokensModel::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Tokens::class, 'Google_Service_Directory_Resource_Tokens');
