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

namespace Google\Service\Logging\Resource;

use Google\Service\Logging\CmekSettings;
use Google\Service\Logging\Settings;

/**
 * The "billingAccounts" collection of methods.
 * Typical usage is:
 *  <code>
 *   $loggingService = new Google\Service\Logging(...);
 *   $billingAccounts = $loggingService->billingAccounts;
 *  </code>
 */
class BillingAccounts extends \Google\Service\Resource
{
  /**
   * Gets the Logging CMEK settings for the given resource.Note: CMEK for the Log
   * Router can be configured for Google Cloud projects, folders, organizations,
   * and billing accounts. Once configured for an organization, it applies to all
   * projects and folders in the Google Cloud organization.See Enabling CMEK for
   * Log Router (https://cloud.google.com/logging/docs/routing/managed-encryption)
   * for more information. (billingAccounts.getCmekSettings)
   *
   * @param string $name Required. The resource for which to retrieve CMEK
   * settings. "projects/[PROJECT_ID]/cmekSettings"
   * "organizations/[ORGANIZATION_ID]/cmekSettings"
   * "billingAccounts/[BILLING_ACCOUNT_ID]/cmekSettings"
   * "folders/[FOLDER_ID]/cmekSettings" For
   * example:"organizations/12345/cmekSettings"Note: CMEK for the Log Router can
   * be configured for Google Cloud projects, folders, organizations, and billing
   * accounts. Once configured for an organization, it applies to all projects and
   * folders in the Google Cloud organization.
   * @param array $optParams Optional parameters.
   * @return CmekSettings
   * @throws \Google\Service\Exception
   */
  public function getCmekSettings($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('getCmekSettings', [$params], CmekSettings::class);
  }
  /**
   * Gets the settings for the given resource.Note: Settings can be retrieved for
   * Google Cloud projects, folders, organizations, and billing accounts.See View
   * default resource settings for Logging
   * (https://cloud.google.com/logging/docs/default-settings#view-org-settings)
   * for more information. (billingAccounts.getSettings)
   *
   * @param string $name Required. The resource for which to retrieve settings.
   * "projects/[PROJECT_ID]/settings" "organizations/[ORGANIZATION_ID]/settings"
   * "billingAccounts/[BILLING_ACCOUNT_ID]/settings"
   * "folders/[FOLDER_ID]/settings" For
   * example:"organizations/12345/settings"Note: Settings can be retrieved for
   * Google Cloud projects, folders, organizations, and billing accounts.
   * @param array $optParams Optional parameters.
   * @return Settings
   * @throws \Google\Service\Exception
   */
  public function getSettings($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('getSettings', [$params], Settings::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(BillingAccounts::class, 'Google_Service_Logging_Resource_BillingAccounts');
