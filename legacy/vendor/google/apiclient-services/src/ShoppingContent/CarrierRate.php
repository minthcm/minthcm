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

namespace Google\Service\ShoppingContent;

class CarrierRate extends \Google\Model
{
  /**
   * @var string
   */
  public $carrierName;
  /**
   * @var string
   */
  public $carrierService;
  protected $flatAdjustmentType = Price::class;
  protected $flatAdjustmentDataType = '';
  /**
   * @var string
   */
  public $name;
  /**
   * @var string
   */
  public $originPostalCode;
  /**
   * @var string
   */
  public $percentageAdjustment;

  /**
   * @param string
   */
  public function setCarrierName($carrierName)
  {
    $this->carrierName = $carrierName;
  }
  /**
   * @return string
   */
  public function getCarrierName()
  {
    return $this->carrierName;
  }
  /**
   * @param string
   */
  public function setCarrierService($carrierService)
  {
    $this->carrierService = $carrierService;
  }
  /**
   * @return string
   */
  public function getCarrierService()
  {
    return $this->carrierService;
  }
  /**
   * @param Price
   */
  public function setFlatAdjustment(Price $flatAdjustment)
  {
    $this->flatAdjustment = $flatAdjustment;
  }
  /**
   * @return Price
   */
  public function getFlatAdjustment()
  {
    return $this->flatAdjustment;
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
   * @param string
   */
  public function setOriginPostalCode($originPostalCode)
  {
    $this->originPostalCode = $originPostalCode;
  }
  /**
   * @return string
   */
  public function getOriginPostalCode()
  {
    return $this->originPostalCode;
  }
  /**
   * @param string
   */
  public function setPercentageAdjustment($percentageAdjustment)
  {
    $this->percentageAdjustment = $percentageAdjustment;
  }
  /**
   * @return string
   */
  public function getPercentageAdjustment()
  {
    return $this->percentageAdjustment;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CarrierRate::class, 'Google_Service_ShoppingContent_CarrierRate');
