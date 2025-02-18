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

namespace Google\Service\GamesConfiguration;

class GamesNumberFormatConfiguration extends \Google\Model
{
  /**
   * @var string
   */
  public $currencyCode;
  /**
   * @var int
   */
  public $numDecimalPlaces;
  /**
   * @var string
   */
  public $numberFormatType;
  protected $suffixType = GamesNumberAffixConfiguration::class;
  protected $suffixDataType = '';

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
   * @param int
   */
  public function setNumDecimalPlaces($numDecimalPlaces)
  {
    $this->numDecimalPlaces = $numDecimalPlaces;
  }
  /**
   * @return int
   */
  public function getNumDecimalPlaces()
  {
    return $this->numDecimalPlaces;
  }
  /**
   * @param string
   */
  public function setNumberFormatType($numberFormatType)
  {
    $this->numberFormatType = $numberFormatType;
  }
  /**
   * @return string
   */
  public function getNumberFormatType()
  {
    return $this->numberFormatType;
  }
  /**
   * @param GamesNumberAffixConfiguration
   */
  public function setSuffix(GamesNumberAffixConfiguration $suffix)
  {
    $this->suffix = $suffix;
  }
  /**
   * @return GamesNumberAffixConfiguration
   */
  public function getSuffix()
  {
    return $this->suffix;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GamesNumberFormatConfiguration::class, 'Google_Service_GamesConfiguration_GamesNumberFormatConfiguration');
