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

namespace Google\Service\Walletobjects\Resource;

use Google\Service\Walletobjects\JwtInsertResponse;
use Google\Service\Walletobjects\JwtResource;

/**
 * The "jwt" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google\Service\Walletobjects(...);
 *   $jwt = $walletobjectsService->jwt;
 *  </code>
 */
class Jwt extends \Google\Service\Resource
{
  /**
   * Inserts the resources in the JWT. (jwt.insert)
   *
   * @param JwtResource $postBody
   * @param array $optParams Optional parameters.
   * @return JwtInsertResponse
   * @throws \Google\Service\Exception
   */
  public function insert(JwtResource $postBody, $optParams = [])
  {
    $params = ['postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('insert', [$params], JwtInsertResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Jwt::class, 'Google_Service_Walletobjects_Resource_Jwt');
