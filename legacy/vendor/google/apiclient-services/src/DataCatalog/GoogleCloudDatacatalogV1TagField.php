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

namespace Google\Service\DataCatalog;

class GoogleCloudDatacatalogV1TagField extends \Google\Model
{
  /**
   * @var bool
   */
  public $boolValue;
  /**
   * @var string
   */
  public $displayName;
  public $doubleValue;
  protected $enumValueType = GoogleCloudDatacatalogV1TagFieldEnumValue::class;
  protected $enumValueDataType = '';
  /**
   * @var int
   */
  public $order;
  /**
   * @var string
   */
  public $richtextValue;
  /**
   * @var string
   */
  public $stringValue;
  /**
   * @var string
   */
  public $timestampValue;

  /**
   * @param bool
   */
  public function setBoolValue($boolValue)
  {
    $this->boolValue = $boolValue;
  }
  /**
   * @return bool
   */
  public function getBoolValue()
  {
    return $this->boolValue;
  }
  /**
   * @param string
   */
  public function setDisplayName($displayName)
  {
    $this->displayName = $displayName;
  }
  /**
   * @return string
   */
  public function getDisplayName()
  {
    return $this->displayName;
  }
  public function setDoubleValue($doubleValue)
  {
    $this->doubleValue = $doubleValue;
  }
  public function getDoubleValue()
  {
    return $this->doubleValue;
  }
  /**
   * @param GoogleCloudDatacatalogV1TagFieldEnumValue
   */
  public function setEnumValue(GoogleCloudDatacatalogV1TagFieldEnumValue $enumValue)
  {
    $this->enumValue = $enumValue;
  }
  /**
   * @return GoogleCloudDatacatalogV1TagFieldEnumValue
   */
  public function getEnumValue()
  {
    return $this->enumValue;
  }
  /**
   * @param int
   */
  public function setOrder($order)
  {
    $this->order = $order;
  }
  /**
   * @return int
   */
  public function getOrder()
  {
    return $this->order;
  }
  /**
   * @param string
   */
  public function setRichtextValue($richtextValue)
  {
    $this->richtextValue = $richtextValue;
  }
  /**
   * @return string
   */
  public function getRichtextValue()
  {
    return $this->richtextValue;
  }
  /**
   * @param string
   */
  public function setStringValue($stringValue)
  {
    $this->stringValue = $stringValue;
  }
  /**
   * @return string
   */
  public function getStringValue()
  {
    return $this->stringValue;
  }
  /**
   * @param string
   */
  public function setTimestampValue($timestampValue)
  {
    $this->timestampValue = $timestampValue;
  }
  /**
   * @return string
   */
  public function getTimestampValue()
  {
    return $this->timestampValue;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDatacatalogV1TagField::class, 'Google_Service_DataCatalog_GoogleCloudDatacatalogV1TagField');
