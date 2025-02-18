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

use Google\Service\ShoppingContent\CollectionStatus;
use Google\Service\ShoppingContent\ListCollectionStatusesResponse;

/**
 * The "collectionstatuses" collection of methods.
 * Typical usage is:
 *  <code>
 *   $contentService = new Google\Service\ShoppingContent(...);
 *   $collectionstatuses = $contentService->collectionstatuses;
 *  </code>
 */
class Collectionstatuses extends \Google\Service\Resource
{
  /**
   * Gets the status of a collection from your Merchant Center account.
   * (collectionstatuses.get)
   *
   * @param string $merchantId Required. The ID of the account that contains the
   * collection. This account cannot be a multi-client account.
   * @param string $collectionId Required. The collectionId of the collection.
   * CollectionId is the same as the REST ID of the collection.
   * @param array $optParams Optional parameters.
   * @return CollectionStatus
   * @throws \Google\Service\Exception
   */
  public function get($merchantId, $collectionId, $optParams = [])
  {
    $params = ['merchantId' => $merchantId, 'collectionId' => $collectionId];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], CollectionStatus::class);
  }
  /**
   * Lists the statuses of the collections in your Merchant Center account.
   * (collectionstatuses.listCollectionstatuses)
   *
   * @param string $merchantId Required. The ID of the account that contains the
   * collection. This account cannot be a multi-client account.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int pageSize The maximum number of collection statuses to return
   * in the response, used for paging. Defaults to 50; values above 1000 will be
   * coerced to 1000.
   * @opt_param string pageToken Token (if provided) to retrieve the subsequent
   * page. All other parameters must match the original call that provided the
   * page token.
   * @return ListCollectionStatusesResponse
   * @throws \Google\Service\Exception
   */
  public function listCollectionstatuses($merchantId, $optParams = [])
  {
    $params = ['merchantId' => $merchantId];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListCollectionStatusesResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Collectionstatuses::class, 'Google_Service_ShoppingContent_Resource_Collectionstatuses');
