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

namespace Google\Service\Firebaseappcheck;

class GoogleFirebaseAppcheckV1ExchangeDeviceCheckTokenRequest extends \Google\Model
{
  /**
   * @var string
   */
  public $deviceToken;
  /**
   * @var bool
   */
  public $limitedUse;

  /**
   * @param string
   */
  public function setDeviceToken($deviceToken)
  {
    $this->deviceToken = $deviceToken;
  }
  /**
   * @return string
   */
  public function getDeviceToken()
  {
    return $this->deviceToken;
  }
  /**
   * @param bool
   */
  public function setLimitedUse($limitedUse)
  {
    $this->limitedUse = $limitedUse;
  }
  /**
   * @return bool
   */
  public function getLimitedUse()
  {
    return $this->limitedUse;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleFirebaseAppcheckV1ExchangeDeviceCheckTokenRequest::class, 'Google_Service_Firebaseappcheck_GoogleFirebaseAppcheckV1ExchangeDeviceCheckTokenRequest');
