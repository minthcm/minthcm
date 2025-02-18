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

namespace Google\Service\ShoppingContent\Resource;

use Google\Service\ShoppingContent\LiaOmnichannelExperience;
use Google\Service\ShoppingContent\LiaSettings as LiaSettingsModel;
use Google\Service\ShoppingContent\LiasettingsCustomBatchRequest;
use Google\Service\ShoppingContent\LiasettingsCustomBatchResponse;
use Google\Service\ShoppingContent\LiasettingsGetAccessibleGmbAccountsResponse;
use Google\Service\ShoppingContent\LiasettingsListPosDataProvidersResponse;
use Google\Service\ShoppingContent\LiasettingsListResponse;
use Google\Service\ShoppingContent\LiasettingsRequestGmbAccessResponse;
use Google\Service\ShoppingContent\LiasettingsRequestInventoryVerificationResponse;
use Google\Service\ShoppingContent\LiasettingsSetInventoryVerificationContactResponse;
use Google\Service\ShoppingContent\LiasettingsSetPosDataProviderResponse;

/**
 * The "liasettings" collection of methods.
 * Typical usage is:
 *  <code>
 *   $contentService = new Google\Service\ShoppingContent(...);
 *   $liasettings = $contentService->liasettings;
 *  </code>
 */
class Liasettings extends \Google\Service\Resource
{
  /**
   * Retrieves and/or updates the LIA settings of multiple accounts in a single
   * request. (liasettings.custombatch)
   *
   * @param LiasettingsCustomBatchRequest $postBody
   * @param array $optParams Optional parameters.
   * @return LiasettingsCustomBatchResponse
   * @throws \Google\Service\Exception
   */
  public function custombatch(LiasettingsCustomBatchRequest $postBody, $optParams = [])
  {
    $params = ['postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('custombatch', [$params], LiasettingsCustomBatchResponse::class);
  }
  /**
   * Retrieves the LIA settings of the account. (liasettings.get)
   *
   * @param string $merchantId The ID of the managing account. If this parameter
   * is not the same as accountId, then this account must be a multi-client
   * account and `accountId` must be the ID of a sub-account of this account.
   * @param string $accountId The ID of the account for which to get or update LIA
   * settings.
   * @param array $optParams Optional parameters.
   * @return LiaSettingsModel
   * @throws \Google\Service\Exception
   */
  public function get($merchantId, $accountId, $optParams = [])
  {
    $params = ['merchantId' => $merchantId, 'accountId' => $accountId];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], LiaSettingsModel::class);
  }
  /**
   * Retrieves the list of accessible Business Profiles.
   * (liasettings.getaccessiblegmbaccounts)
   *
   * @param string $merchantId The ID of the managing account. If this parameter
   * is not the same as accountId, then this account must be a multi-client
   * account and `accountId` must be the ID of a sub-account of this account.
   * @param string $accountId The ID of the account for which to retrieve
   * accessible Business Profiles.
   * @param array $optParams Optional parameters.
   * @return LiasettingsGetAccessibleGmbAccountsResponse
   * @throws \Google\Service\Exception
   */
  public function getaccessiblegmbaccounts($merchantId, $accountId, $optParams = [])
  {
    $params = ['merchantId' => $merchantId, 'accountId' => $accountId];
    $params = array_merge($params, $optParams);
    return $this->call('getaccessiblegmbaccounts', [$params], LiasettingsGetAccessibleGmbAccountsResponse::class);
  }
  /**
   * Lists the LIA settings of the sub-accounts in your Merchant Center account.
   * (liasettings.listLiasettings)
   *
   * @param string $merchantId The ID of the managing account. This must be a
   * multi-client account.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string maxResults The maximum number of LIA settings to return in
   * the response, used for paging.
   * @opt_param string pageToken The token returned by the previous request.
   * @return LiasettingsListResponse
   * @throws \Google\Service\Exception
   */
  public function listLiasettings($merchantId, $optParams = [])
  {
    $params = ['merchantId' => $merchantId];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], LiasettingsListResponse::class);
  }
  /**
   * Retrieves the list of POS data providers that have active settings for the
   * all eiligible countries. (liasettings.listposdataproviders)
   *
   * @param array $optParams Optional parameters.
   * @return LiasettingsListPosDataProvidersResponse
   * @throws \Google\Service\Exception
   */
  public function listposdataproviders($optParams = [])
  {
    $params = [];
    $params = array_merge($params, $optParams);
    return $this->call('listposdataproviders', [$params], LiasettingsListPosDataProvidersResponse::class);
  }
  /**
   * Requests access to a specified Business Profile.
   * (liasettings.requestgmbaccess)
   *
   * @param string $merchantId The ID of the managing account. If this parameter
   * is not the same as accountId, then this account must be a multi-client
   * account and `accountId` must be the ID of a sub-account of this account.
   * @param string $accountId The ID of the account for which Business Profile
   * access is requested.
   * @param string $gmbEmail The email of the Business Profile.
   * @param array $optParams Optional parameters.
   * @return LiasettingsRequestGmbAccessResponse
   * @throws \Google\Service\Exception
   */
  public function requestgmbaccess($merchantId, $accountId, $gmbEmail, $optParams = [])
  {
    $params = ['merchantId' => $merchantId, 'accountId' => $accountId, 'gmbEmail' => $gmbEmail];
    $params = array_merge($params, $optParams);
    return $this->call('requestgmbaccess', [$params], LiasettingsRequestGmbAccessResponse::class);
  }
  /**
   * Requests inventory validation for the specified country.
   * (liasettings.requestinventoryverification)
   *
   * @param string $merchantId The ID of the managing account. If this parameter
   * is not the same as accountId, then this account must be a multi-client
   * account and `accountId` must be the ID of a sub-account of this account.
   * @param string $accountId The ID of the account that manages the order. This
   * cannot be a multi-client account.
   * @param string $country The country for which inventory validation is
   * requested.
   * @param array $optParams Optional parameters.
   * @return LiasettingsRequestInventoryVerificationResponse
   * @throws \Google\Service\Exception
   */
  public function requestinventoryverification($merchantId, $accountId, $country, $optParams = [])
  {
    $params = ['merchantId' => $merchantId, 'accountId' => $accountId, 'country' => $country];
    $params = array_merge($params, $optParams);
    return $this->call('requestinventoryverification', [$params], LiasettingsRequestInventoryVerificationResponse::class);
  }
  /**
   * Sets the inventory verification contract for the specified country.
   * (liasettings.setinventoryverificationcontact)
   *
   * @param string $merchantId The ID of the managing account. If this parameter
   * is not the same as accountId, then this account must be a multi-client
   * account and `accountId` must be the ID of a sub-account of this account.
   * @param string $accountId The ID of the account that manages the order. This
   * cannot be a multi-client account.
   * @param string $country The country for which inventory verification is
   * requested.
   * @param string $language The language for which inventory verification is
   * requested.
   * @param string $contactName The name of the inventory verification contact.
   * @param string $contactEmail The email of the inventory verification contact.
   * @param array $optParams Optional parameters.
   * @return LiasettingsSetInventoryVerificationContactResponse
   * @throws \Google\Service\Exception
   */
  public function setinventoryverificationcontact($merchantId, $accountId, $country, $language, $contactName, $contactEmail, $optParams = [])
  {
    $params = ['merchantId' => $merchantId, 'accountId' => $accountId, 'country' => $country, 'language' => $language, 'contactName' => $contactName, 'contactEmail' => $contactEmail];
    $params = array_merge($params, $optParams);
    return $this->call('setinventoryverificationcontact', [$params], LiasettingsSetInventoryVerificationContactResponse::class);
  }
  /**
   * Sets the omnichannel experience for the specified country. Only supported for
   * merchants whose POS data provider is trusted to enable the corresponding
   * experience. For more context, see these help articles [about
   * LFP](https://support.google.com/merchants/answer/7676652) and [how to get
   * started](https://support.google.com/merchants/answer/7676578) with it.
   * (liasettings.setomnichannelexperience)
   *
   * @param string $merchantId The ID of the managing account. If this parameter
   * is not the same as accountId, then this account must be a multi-client
   * account and `accountId` must be the ID of a sub-account of this account.
   * @param string $accountId The ID of the account for which to retrieve
   * accessible Business Profiles.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string country The CLDR country code (for example, "US") for which
   * the omnichannel experience is selected.
   * @opt_param string lsfType The Local Store Front (LSF) type for this country.
   * Acceptable values are: - "`ghlsf`" (Google-Hosted Local Store Front) -
   * "`mhlsfBasic`" (Merchant-Hosted Local Store Front Basic) - "`mhlsfFull`"
   * (Merchant-Hosted Local Store Front Full) More details about these types can
   * be found here.
   * @opt_param string pickupTypes The Pickup types for this country. Acceptable
   * values are: - "`pickupToday`" - "`pickupLater`"
   * @return LiaOmnichannelExperience
   * @throws \Google\Service\Exception
   */
  public function setomnichannelexperience($merchantId, $accountId, $optParams = [])
  {
    $params = ['merchantId' => $merchantId, 'accountId' => $accountId];
    $params = array_merge($params, $optParams);
    return $this->call('setomnichannelexperience', [$params], LiaOmnichannelExperience::class);
  }
  /**
   * Sets the POS data provider for the specified country.
   * (liasettings.setposdataprovider)
   *
   * @param string $merchantId The ID of the managing account. If this parameter
   * is not the same as accountId, then this account must be a multi-client
   * account and `accountId` must be the ID of a sub-account of this account.
   * @param string $accountId The ID of the account for which to retrieve
   * accessible Business Profiles.
   * @param string $country The country for which the POS data provider is
   * selected.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string posDataProviderId The ID of POS data provider.
   * @opt_param string posExternalAccountId The account ID by which this merchant
   * is known to the POS data provider.
   * @return LiasettingsSetPosDataProviderResponse
   * @throws \Google\Service\Exception
   */
  public function setposdataprovider($merchantId, $accountId, $country, $optParams = [])
  {
    $params = ['merchantId' => $merchantId, 'accountId' => $accountId, 'country' => $country];
    $params = array_merge($params, $optParams);
    return $this->call('setposdataprovider', [$params], LiasettingsSetPosDataProviderResponse::class);
  }
  /**
   * Updates the LIA settings of the account. Any fields that are not provided are
   * deleted from the resource. (liasettings.update)
   *
   * @param string $merchantId The ID of the managing account. If this parameter
   * is not the same as accountId, then this account must be a multi-client
   * account and `accountId` must be the ID of a sub-account of this account.
   * @param string $accountId The ID of the account for which to get or update LIA
   * settings.
   * @param LiaSettingsModel $postBody
   * @param array $optParams Optional parameters.
   * @return LiaSettingsModel
   * @throws \Google\Service\Exception
   */
  public function update($merchantId, $accountId, LiaSettingsModel $postBody, $optParams = [])
  {
    $params = ['merchantId' => $merchantId, 'accountId' => $accountId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('update', [$params], LiaSettingsModel::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Liasettings::class, 'Google_Service_ShoppingContent_Resource_Liasettings');
