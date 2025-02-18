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

namespace Google\Service\Cloudchannel\Resource;

use Google\Service\Cloudchannel\GoogleCloudChannelV1ListProductsResponse;

/**
 * The "products" collection of methods.
 * Typical usage is:
 *  <code>
 *   $cloudchannelService = new Google\Service\Cloudchannel(...);
 *   $products = $cloudchannelService->products;
 *  </code>
 */
class Products extends \Google\Service\Resource
{
  /**
   * Lists the Products the reseller is authorized to sell. Possible error codes:
   * * INVALID_ARGUMENT: Required request parameters are missing or invalid.
   * (products.listProducts)
   *
   * @param array $optParams Optional parameters.
   *
   * @opt_param string account Required. The resource name of the reseller
   * account. Format: accounts/{account_id}.
   * @opt_param string languageCode Optional. The BCP-47 language code. For
   * example, "en-US". The response will localize in the corresponding language
   * code, if specified. The default value is "en-US".
   * @opt_param int pageSize Optional. Requested page size. Server might return
   * fewer results than requested. If unspecified, returns at most 100 Products.
   * The maximum value is 1000; the server will coerce values above 1000.
   * @opt_param string pageToken Optional. A token for a page of results other
   * than the first page.
   * @return GoogleCloudChannelV1ListProductsResponse
   * @throws \Google\Service\Exception
   */
  public function listProducts($optParams = [])
  {
    $params = [];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], GoogleCloudChannelV1ListProductsResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Products::class, 'Google_Service_Cloudchannel_Resource_Products');
