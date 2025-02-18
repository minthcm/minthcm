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

namespace Google\Service\AndroidPublisher;

class ManagedProductTaxAndComplianceSettings extends \Google\Model
{
  /**
   * @var string
   */
  public $eeaWithdrawalRightType;
  /**
   * @var bool
   */
  public $isTokenizedDigitalAsset;
  protected $taxRateInfoByRegionCodeType = RegionalTaxRateInfo::class;
  protected $taxRateInfoByRegionCodeDataType = 'map';

  /**
   * @param string
   */
  public function setEeaWithdrawalRightType($eeaWithdrawalRightType)
  {
    $this->eeaWithdrawalRightType = $eeaWithdrawalRightType;
  }
  /**
   * @return string
   */
  public function getEeaWithdrawalRightType()
  {
    return $this->eeaWithdrawalRightType;
  }
  /**
   * @param bool
   */
  public function setIsTokenizedDigitalAsset($isTokenizedDigitalAsset)
  {
    $this->isTokenizedDigitalAsset = $isTokenizedDigitalAsset;
  }
  /**
   * @return bool
   */
  public function getIsTokenizedDigitalAsset()
  {
    return $this->isTokenizedDigitalAsset;
  }
  /**
   * @param RegionalTaxRateInfo[]
   */
  public function setTaxRateInfoByRegionCode($taxRateInfoByRegionCode)
  {
    $this->taxRateInfoByRegionCode = $taxRateInfoByRegionCode;
  }
  /**
   * @return RegionalTaxRateInfo[]
   */
  public function getTaxRateInfoByRegionCode()
  {
    return $this->taxRateInfoByRegionCode;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ManagedProductTaxAndComplianceSettings::class, 'Google_Service_AndroidPublisher_ManagedProductTaxAndComplianceSettings');
