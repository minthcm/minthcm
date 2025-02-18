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

class AccountReturnCarrier extends \Google\Model
{
  /**
   * @var string
   */
  public $carrierAccountId;
  /**
   * @var string
   */
  public $carrierAccountName;
  /**
   * @var string
   */
  public $carrierAccountNumber;
  /**
   * @var string
   */
  public $carrierCode;

  /**
   * @param string
   */
  public function setCarrierAccountId($carrierAccountId)
  {
    $this->carrierAccountId = $carrierAccountId;
  }
  /**
   * @return string
   */
  public function getCarrierAccountId()
  {
    return $this->carrierAccountId;
  }
  /**
   * @param string
   */
  public function setCarrierAccountName($carrierAccountName)
  {
    $this->carrierAccountName = $carrierAccountName;
  }
  /**
   * @return string
   */
  public function getCarrierAccountName()
  {
    return $this->carrierAccountName;
  }
  /**
   * @param string
   */
  public function setCarrierAccountNumber($carrierAccountNumber)
  {
    $this->carrierAccountNumber = $carrierAccountNumber;
  }
  /**
   * @return string
   */
  public function getCarrierAccountNumber()
  {
    return $this->carrierAccountNumber;
  }
  /**
   * @param string
   */
  public function setCarrierCode($carrierCode)
  {
    $this->carrierCode = $carrierCode;
  }
  /**
   * @return string
   */
  public function getCarrierCode()
  {
    return $this->carrierCode;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AccountReturnCarrier::class, 'Google_Service_ShoppingContent_AccountReturnCarrier');
