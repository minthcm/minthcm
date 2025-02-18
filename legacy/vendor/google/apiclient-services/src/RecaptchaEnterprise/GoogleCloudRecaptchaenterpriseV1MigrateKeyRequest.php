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

namespace Google\Service\RecaptchaEnterprise;

class GoogleCloudRecaptchaenterpriseV1MigrateKeyRequest extends \Google\Model
{
  /**
   * @var bool
   */
  public $skipBillingCheck;

  /**
   * @param bool
   */
  public function setSkipBillingCheck($skipBillingCheck)
  {
    $this->skipBillingCheck = $skipBillingCheck;
  }
  /**
   * @return bool
   */
  public function getSkipBillingCheck()
  {
    return $this->skipBillingCheck;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudRecaptchaenterpriseV1MigrateKeyRequest::class, 'Google_Service_RecaptchaEnterprise_GoogleCloudRecaptchaenterpriseV1MigrateKeyRequest');
