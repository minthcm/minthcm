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

namespace Google\Service\Compute;

class SecurityPolicyRuleMatcherExprOptionsRecaptchaOptions extends \Google\Collection
{
  protected $collection_key = 'sessionTokenSiteKeys';
  /**
   * @var string[]
   */
  public $actionTokenSiteKeys;
  /**
   * @var string[]
   */
  public $sessionTokenSiteKeys;

  /**
   * @param string[]
   */
  public function setActionTokenSiteKeys($actionTokenSiteKeys)
  {
    $this->actionTokenSiteKeys = $actionTokenSiteKeys;
  }
  /**
   * @return string[]
   */
  public function getActionTokenSiteKeys()
  {
    return $this->actionTokenSiteKeys;
  }
  /**
   * @param string[]
   */
  public function setSessionTokenSiteKeys($sessionTokenSiteKeys)
  {
    $this->sessionTokenSiteKeys = $sessionTokenSiteKeys;
  }
  /**
   * @return string[]
   */
  public function getSessionTokenSiteKeys()
  {
    return $this->sessionTokenSiteKeys;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SecurityPolicyRuleMatcherExprOptionsRecaptchaOptions::class, 'Google_Service_Compute_SecurityPolicyRuleMatcherExprOptionsRecaptchaOptions');
