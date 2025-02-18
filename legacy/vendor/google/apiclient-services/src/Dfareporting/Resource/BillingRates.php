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

use Google\Service\Dfareporting\BillingRatesListResponse;

/**
 * The "billingRates" collection of methods.
 * Typical usage is:
 *  <code>
 *   $dfareportingService = new Google\Service\Dfareporting(...);
 *   $billingRates = $dfareportingService->billingRates;
 *  </code>
 */
class BillingRates extends \Google\Service\Resource
{
  /**
   * Retrieves a list of billing rates. This method supports paging.
   * (billingRates.listBillingRates)
   *
   * @param string $profileId User profile ID associated with this request.
   * @param string $billingProfileId Billing profile ID of this billing rate.
   * @param array $optParams Optional parameters.
   * @return BillingRatesListResponse
   * @throws \Google\Service\Exception
   */
  public function listBillingRates($profileId, $billingProfileId, $optParams = [])
  {
    $params = ['profileId' => $profileId, 'billingProfileId' => $billingProfileId];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], BillingRatesListResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(BillingRates::class, 'Google_Service_Dfareporting_Resource_BillingRates');
