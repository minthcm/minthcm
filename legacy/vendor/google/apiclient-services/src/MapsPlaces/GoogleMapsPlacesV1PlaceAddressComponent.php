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

namespace Google\Service\MapsPlaces;

class GoogleMapsPlacesV1PlaceAddressComponent extends \Google\Collection
{
  protected $collection_key = 'types';
  /**
   * @var string
   */
  public $languageCode;
  /**
   * @var string
   */
  public $longText;
  /**
   * @var string
   */
  public $shortText;
  /**
   * @var string[]
   */
  public $types;

  /**
   * @param string
   */
  public function setLanguageCode($languageCode)
  {
    $this->languageCode = $languageCode;
  }
  /**
   * @return string
   */
  public function getLanguageCode()
  {
    return $this->languageCode;
  }
  /**
   * @param string
   */
  public function setLongText($longText)
  {
    $this->longText = $longText;
  }
  /**
   * @return string
   */
  public function getLongText()
  {
    return $this->longText;
  }
  /**
   * @param string
   */
  public function setShortText($shortText)
  {
    $this->shortText = $shortText;
  }
  /**
   * @return string
   */
  public function getShortText()
  {
    return $this->shortText;
  }
  /**
   * @param string[]
   */
  public function setTypes($types)
  {
    $this->types = $types;
  }
  /**
   * @return string[]
   */
  public function getTypes()
  {
    return $this->types;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleMapsPlacesV1PlaceAddressComponent::class, 'Google_Service_MapsPlaces_GoogleMapsPlacesV1PlaceAddressComponent');
