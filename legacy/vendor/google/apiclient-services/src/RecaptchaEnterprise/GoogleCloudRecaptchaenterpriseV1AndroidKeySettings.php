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

class GoogleCloudRecaptchaenterpriseV1AndroidKeySettings extends \Google\Collection
{
  protected $collection_key = 'allowedPackageNames';
  /**
   * @var bool
   */
  public $allowAllPackageNames;
  /**
   * @var string[]
   */
  public $allowedPackageNames;
  /**
   * @var bool
   */
  public $supportNonGoogleAppStoreDistribution;

  /**
   * @param bool
   */
  public function setAllowAllPackageNames($allowAllPackageNames)
  {
    $this->allowAllPackageNames = $allowAllPackageNames;
  }
  /**
   * @return bool
   */
  public function getAllowAllPackageNames()
  {
    return $this->allowAllPackageNames;
  }
  /**
   * @param string[]
   */
  public function setAllowedPackageNames($allowedPackageNames)
  {
    $this->allowedPackageNames = $allowedPackageNames;
  }
  /**
   * @return string[]
   */
  public function getAllowedPackageNames()
  {
    return $this->allowedPackageNames;
  }
  /**
   * @param bool
   */
  public function setSupportNonGoogleAppStoreDistribution($supportNonGoogleAppStoreDistribution)
  {
    $this->supportNonGoogleAppStoreDistribution = $supportNonGoogleAppStoreDistribution;
  }
  /**
   * @return bool
   */
  public function getSupportNonGoogleAppStoreDistribution()
  {
    return $this->supportNonGoogleAppStoreDistribution;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudRecaptchaenterpriseV1AndroidKeySettings::class, 'Google_Service_RecaptchaEnterprise_GoogleCloudRecaptchaenterpriseV1AndroidKeySettings');
