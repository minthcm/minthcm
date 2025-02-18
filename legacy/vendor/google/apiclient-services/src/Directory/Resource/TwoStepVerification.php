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

/**
 * The "twoStepVerification" collection of methods.
 * Typical usage is:
 *  <code>
 *   $adminService = new Google\Service\Directory(...);
 *   $twoStepVerification = $adminService->twoStepVerification;
 *  </code>
 */
class TwoStepVerification extends \Google\Service\Resource
{
  /**
   * Turns off 2-Step Verification for user. (twoStepVerification.turnOff)
   *
   * @param string $userKey Identifies the user in the API request. The value can
   * be the user's primary email address, alias email address, or unique user ID.
   * @param array $optParams Optional parameters.
   * @throws \Google\Service\Exception
   */
  public function turnOff($userKey, $optParams = [])
  {
    $params = ['userKey' => $userKey];
    $params = array_merge($params, $optParams);
    return $this->call('turnOff', [$params]);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(TwoStepVerification::class, 'Google_Service_Directory_Resource_TwoStepVerification');
