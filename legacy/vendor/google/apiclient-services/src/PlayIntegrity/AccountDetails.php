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

namespace Google\Service\PlayIntegrity;

class AccountDetails extends \Google\Model
{
  protected $accountActivityType = AccountActivity::class;
  protected $accountActivityDataType = '';
  /**
   * @var string
   */
  public $appLicensingVerdict;

  /**
   * @param AccountActivity
   */
  public function setAccountActivity(AccountActivity $accountActivity)
  {
    $this->accountActivity = $accountActivity;
  }
  /**
   * @return AccountActivity
   */
  public function getAccountActivity()
  {
    return $this->accountActivity;
  }
  /**
   * @param string
   */
  public function setAppLicensingVerdict($appLicensingVerdict)
  {
    $this->appLicensingVerdict = $appLicensingVerdict;
  }
  /**
   * @return string
   */
  public function getAppLicensingVerdict()
  {
    return $this->appLicensingVerdict;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AccountDetails::class, 'Google_Service_PlayIntegrity_AccountDetails');
