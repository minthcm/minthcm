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

namespace Google\Service\Recommender;

class GoogleCloudRecommenderV1CostProjection extends \Google\Model
{
  protected $costType = GoogleTypeMoney::class;
  protected $costDataType = '';
  protected $costInLocalCurrencyType = GoogleTypeMoney::class;
  protected $costInLocalCurrencyDataType = '';
  /**
   * @var string
   */
  public $duration;

  /**
   * @param GoogleTypeMoney
   */
  public function setCost(GoogleTypeMoney $cost)
  {
    $this->cost = $cost;
  }
  /**
   * @return GoogleTypeMoney
   */
  public function getCost()
  {
    return $this->cost;
  }
  /**
   * @param GoogleTypeMoney
   */
  public function setCostInLocalCurrency(GoogleTypeMoney $costInLocalCurrency)
  {
    $this->costInLocalCurrency = $costInLocalCurrency;
  }
  /**
   * @return GoogleTypeMoney
   */
  public function getCostInLocalCurrency()
  {
    return $this->costInLocalCurrency;
  }
  /**
   * @param string
   */
  public function setDuration($duration)
  {
    $this->duration = $duration;
  }
  /**
   * @return string
   */
  public function getDuration()
  {
    return $this->duration;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudRecommenderV1CostProjection::class, 'Google_Service_Recommender_GoogleCloudRecommenderV1CostProjection');
