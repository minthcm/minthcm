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

namespace Google\Service\Solar;

class FinancedPurchaseSavings extends \Google\Model
{
  protected $annualLoanPaymentType = Money::class;
  protected $annualLoanPaymentDataType = '';
  /**
   * @var float
   */
  public $loanInterestRate;
  protected $rebateValueType = Money::class;
  protected $rebateValueDataType = '';
  protected $savingsType = SavingsOverTime::class;
  protected $savingsDataType = '';

  /**
   * @param Money
   */
  public function setAnnualLoanPayment(Money $annualLoanPayment)
  {
    $this->annualLoanPayment = $annualLoanPayment;
  }
  /**
   * @return Money
   */
  public function getAnnualLoanPayment()
  {
    return $this->annualLoanPayment;
  }
  /**
   * @param float
   */
  public function setLoanInterestRate($loanInterestRate)
  {
    $this->loanInterestRate = $loanInterestRate;
  }
  /**
   * @return float
   */
  public function getLoanInterestRate()
  {
    return $this->loanInterestRate;
  }
  /**
   * @param Money
   */
  public function setRebateValue(Money $rebateValue)
  {
    $this->rebateValue = $rebateValue;
  }
  /**
   * @return Money
   */
  public function getRebateValue()
  {
    return $this->rebateValue;
  }
  /**
   * @param SavingsOverTime
   */
  public function setSavings(SavingsOverTime $savings)
  {
    $this->savings = $savings;
  }
  /**
   * @return SavingsOverTime
   */
  public function getSavings()
  {
    return $this->savings;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(FinancedPurchaseSavings::class, 'Google_Service_Solar_FinancedPurchaseSavings');
