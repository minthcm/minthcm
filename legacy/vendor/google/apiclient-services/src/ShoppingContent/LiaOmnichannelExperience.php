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

class LiaOmnichannelExperience extends \Google\Collection
{
  protected $collection_key = 'pickupTypes';
  /**
   * @var string
   */
  public $country;
  /**
   * @var string
   */
  public $lsfType;
  /**
   * @var string[]
   */
  public $pickupTypes;

  /**
   * @param string
   */
  public function setCountry($country)
  {
    $this->country = $country;
  }
  /**
   * @return string
   */
  public function getCountry()
  {
    return $this->country;
  }
  /**
   * @param string
   */
  public function setLsfType($lsfType)
  {
    $this->lsfType = $lsfType;
  }
  /**
   * @return string
   */
  public function getLsfType()
  {
    return $this->lsfType;
  }
  /**
   * @param string[]
   */
  public function setPickupTypes($pickupTypes)
  {
    $this->pickupTypes = $pickupTypes;
  }
  /**
   * @return string[]
   */
  public function getPickupTypes()
  {
    return $this->pickupTypes;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(LiaOmnichannelExperience::class, 'Google_Service_ShoppingContent_LiaOmnichannelExperience');
