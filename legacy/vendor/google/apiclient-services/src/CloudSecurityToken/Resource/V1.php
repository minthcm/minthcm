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

namespace Google\Service\CloudSecurityToken\Resource;

use Google\Service\CloudSecurityToken\GoogleIdentityStsV1ExchangeTokenRequest;
use Google\Service\CloudSecurityToken\GoogleIdentityStsV1ExchangeTokenResponse;

/**
 * The "v1" collection of methods.
 * Typical usage is:
 *  <code>
 *   $stsService = new Google\Service\CloudSecurityToken(...);
 *   $v1 = $stsService->v1;
 *  </code>
 */
class V1 extends \Google\Service\Resource
{
  /**
   * Exchanges a credential for a Google OAuth 2.0 access token. The token asserts
   * an external identity within an identity pool, or it applies a Credential
   * Access Boundary to a Google access token. Note that workforce pools do not
   * support Credential Access Boundaries. When you call this method, do not send
   * the `Authorization` HTTP header in the request. This method does not require
   * the `Authorization` header, and using the header can cause the request to
   * fail. (v1.token)
   *
   * @param GoogleIdentityStsV1ExchangeTokenRequest $postBody
   * @param array $optParams Optional parameters.
   * @return GoogleIdentityStsV1ExchangeTokenResponse
   * @throws \Google\Service\Exception
   */
  public function token(GoogleIdentityStsV1ExchangeTokenRequest $postBody, $optParams = [])
  {
    $params = ['postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('token', [$params], GoogleIdentityStsV1ExchangeTokenResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(V1::class, 'Google_Service_CloudSecurityToken_Resource_V1');
