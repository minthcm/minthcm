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

namespace Google\Service\Books;

class SeriesSeriesSeriesSubscriptionReleaseInfoNextReleaseInfo extends \Google\Model
{
  public $amountInMicros;
  /**
   * @var string
   */
  public $currencyCode;
  /**
   * @var string
   */
  public $releaseNumber;
  /**
   * @var string
   */
  public $releaseTime;

  public function setAmountInMicros($amountInMicros)
  {
    $this->amountInMicros = $amountInMicros;
  }
  public function getAmountInMicros()
  {
    return $this->amountInMicros;
  }
  /**
   * @param string
   */
  public function setCurrencyCode($currencyCode)
  {
    $this->currencyCode = $currencyCode;
  }
  /**
   * @return string
   */
  public function getCurrencyCode()
  {
    return $this->currencyCode;
  }
  /**
   * @param string
   */
  public function setReleaseNumber($releaseNumber)
  {
    $this->releaseNumber = $releaseNumber;
  }
  /**
   * @return string
   */
  public function getReleaseNumber()
  {
    return $this->releaseNumber;
  }
  /**
   * @param string
   */
  public function setReleaseTime($releaseTime)
  {
    $this->releaseTime = $releaseTime;
  }
  /**
   * @return string
   */
  public function getReleaseTime()
  {
    return $this->releaseTime;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SeriesSeriesSeriesSubscriptionReleaseInfoNextReleaseInfo::class, 'Google_Service_Books_SeriesSeriesSeriesSubscriptionReleaseInfoNextReleaseInfo');
