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

namespace Google\Service\PaymentsResellerSubscription;

class GoogleCloudPaymentsResellerSubscriptionV1EntitleSubscriptionRequestLineItemEntitlementDetails extends \Google\Collection
{
  protected $collection_key = 'products';
  /**
   * @var int
   */
  public $lineItemIndex;
  /**
   * @var string[]
   */
  public $products;

  /**
   * @param int
   */
  public function setLineItemIndex($lineItemIndex)
  {
    $this->lineItemIndex = $lineItemIndex;
  }
  /**
   * @return int
   */
  public function getLineItemIndex()
  {
    return $this->lineItemIndex;
  }
  /**
   * @param string[]
   */
  public function setProducts($products)
  {
    $this->products = $products;
  }
  /**
   * @return string[]
   */
  public function getProducts()
  {
    return $this->products;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudPaymentsResellerSubscriptionV1EntitleSubscriptionRequestLineItemEntitlementDetails::class, 'Google_Service_PaymentsResellerSubscription_GoogleCloudPaymentsResellerSubscriptionV1EntitleSubscriptionRequestLineItemEntitlementDetails');
