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

namespace Google\Service\Dfareporting\Resource;

use Google\Service\Dfareporting\PostalCode;
use Google\Service\Dfareporting\PostalCodesListResponse;

/**
 * The "postalCodes" collection of methods.
 * Typical usage is:
 *  <code>
 *   $dfareportingService = new Google\Service\Dfareporting(...);
 *   $postalCodes = $dfareportingService->postalCodes;
 *  </code>
 */
class PostalCodes extends \Google\Service\Resource
{
  /**
   * Gets one postal code by ID. (postalCodes.get)
   *
   * @param string $profileId User profile ID associated with this request.
   * @param string $code Postal code ID.
   * @param array $optParams Optional parameters.
   * @return PostalCode
   * @throws \Google\Service\Exception
   */
  public function get($profileId, $code, $optParams = [])
  {
    $params = ['profileId' => $profileId, 'code' => $code];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], PostalCode::class);
  }
  /**
   * Retrieves a list of postal codes. (postalCodes.listPostalCodes)
   *
   * @param string $profileId User profile ID associated with this request.
   * @param array $optParams Optional parameters.
   * @return PostalCodesListResponse
   * @throws \Google\Service\Exception
   */
  public function listPostalCodes($profileId, $optParams = [])
  {
    $params = ['profileId' => $profileId];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], PostalCodesListResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(PostalCodes::class, 'Google_Service_Dfareporting_Resource_PostalCodes');
