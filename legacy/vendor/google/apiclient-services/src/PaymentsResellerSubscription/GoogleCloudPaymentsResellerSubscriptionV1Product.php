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

class GoogleCloudPaymentsResellerSubscriptionV1Product extends \Google\Collection
{
  protected $collection_key = 'titles';
  protected $bundleDetailsType = ProductBundleDetails::class;
  protected $bundleDetailsDataType = '';
  protected $finiteBillingCycleDetailsType = GoogleCloudPaymentsResellerSubscriptionV1FiniteBillingCycleDetails::class;
  protected $finiteBillingCycleDetailsDataType = '';
  /**
   * @var string
   */
  public $name;
  protected $priceConfigsType = GoogleCloudPaymentsResellerSubscriptionV1ProductPriceConfig::class;
  protected $priceConfigsDataType = 'array';
  /**
   * @var string
   */
  public $productType;
  /**
   * @var string[]
   */
  public $regionCodes;
  protected $subscriptionBillingCycleDurationType = GoogleCloudPaymentsResellerSubscriptionV1Duration::class;
  protected $subscriptionBillingCycleDurationDataType = '';
  protected $titlesType = GoogleTypeLocalizedText::class;
  protected $titlesDataType = 'array';

  /**
   * @param ProductBundleDetails
   */
  public function setBundleDetails(ProductBundleDetails $bundleDetails)
  {
    $this->bundleDetails = $bundleDetails;
  }
  /**
   * @return ProductBundleDetails
   */
  public function getBundleDetails()
  {
    return $this->bundleDetails;
  }
  /**
   * @param GoogleCloudPaymentsResellerSubscriptionV1FiniteBillingCycleDetails
   */
  public function setFiniteBillingCycleDetails(GoogleCloudPaymentsResellerSubscriptionV1FiniteBillingCycleDetails $finiteBillingCycleDetails)
  {
    $this->finiteBillingCycleDetails = $finiteBillingCycleDetails;
  }
  /**
   * @return GoogleCloudPaymentsResellerSubscriptionV1FiniteBillingCycleDetails
   */
  public function getFiniteBillingCycleDetails()
  {
    return $this->finiteBillingCycleDetails;
  }
  /**
   * @param string
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
  /**
   * @param GoogleCloudPaymentsResellerSubscriptionV1ProductPriceConfig[]
   */
  public function setPriceConfigs($priceConfigs)
  {
    $this->priceConfigs = $priceConfigs;
  }
  /**
   * @return GoogleCloudPaymentsResellerSubscriptionV1ProductPriceConfig[]
   */
  public function getPriceConfigs()
  {
    return $this->priceConfigs;
  }
  /**
   * @param string
   */
  public function setProductType($productType)
  {
    $this->productType = $productType;
  }
  /**
   * @return string
   */
  public function getProductType()
  {
    return $this->productType;
  }
  /**
   * @param string[]
   */
  public function setRegionCodes($regionCodes)
  {
    $this->regionCodes = $regionCodes;
  }
  /**
   * @return string[]
   */
  public function getRegionCodes()
  {
    return $this->regionCodes;
  }
  /**
   * @param GoogleCloudPaymentsResellerSubscriptionV1Duration
   */
  public function setSubscriptionBillingCycleDuration(GoogleCloudPaymentsResellerSubscriptionV1Duration $subscriptionBillingCycleDuration)
  {
    $this->subscriptionBillingCycleDuration = $subscriptionBillingCycleDuration;
  }
  /**
   * @return GoogleCloudPaymentsResellerSubscriptionV1Duration
   */
  public function getSubscriptionBillingCycleDuration()
  {
    return $this->subscriptionBillingCycleDuration;
  }
  /**
   * @param GoogleTypeLocalizedText[]
   */
  public function setTitles($titles)
  {
    $this->titles = $titles;
  }
  /**
   * @return GoogleTypeLocalizedText[]
   */
  public function getTitles()
  {
    return $this->titles;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudPaymentsResellerSubscriptionV1Product::class, 'Google_Service_PaymentsResellerSubscription_GoogleCloudPaymentsResellerSubscriptionV1Product');
