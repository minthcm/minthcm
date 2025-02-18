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

namespace Google\Service\Script;

class Value extends \Google\Model
{
  /**
   * @var bool
   */
  public $boolValue;
  /**
   * @var string
   */
  public $bytesValue;
  /**
   * @var string
   */
  public $dateValue;
  protected $listValueType = ListValue::class;
  protected $listValueDataType = '';
  /**
   * @var string
   */
  public $nullValue;
  public $numberValue;
  /**
   * @var array[]
   */
  public $protoValue;
  /**
   * @var string
   */
  public $stringValue;
  protected $structValueType = Struct::class;
  protected $structValueDataType = '';

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
  public function setBytesValue($bytesValue)
  {
    $this->bytesValue = $bytesValue;
  }
  /**
   * @return string
   */
  public function getBytesValue()
  {
    return $this->bytesValue;
  }
  /**
   * @param string
   */
  public function setDateValue($dateValue)
  {
    $this->dateValue = $dateValue;
  }
  /**
   * @return string
   */
  public function getDateValue()
  {
    return $this->dateValue;
  }
  /**
   * @param ListValue
   */
  public function setListValue(ListValue $listValue)
  {
    $this->listValue = $listValue;
  }
  /**
   * @return ListValue
   */
  public function getListValue()
  {
    return $this->listValue;
  }
  /**
   * @param string
   */
  public function setNullValue($nullValue)
  {
    $this->nullValue = $nullValue;
  }
  /**
   * @return string
   */
  public function getNullValue()
  {
    return $this->nullValue;
  }
  public function setNumberValue($numberValue)
  {
    $this->numberValue = $numberValue;
  }
  public function getNumberValue()
  {
    return $this->numberValue;
  }
  /**
   * @param array[]
   */
  public function setProtoValue($protoValue)
  {
    $this->protoValue = $protoValue;
  }
  /**
   * @return array[]
   */
  public function getProtoValue()
  {
    return $this->protoValue;
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
   * @param Struct
   */
  public function setStructValue(Struct $structValue)
  {
    $this->structValue = $structValue;
  }
  /**
   * @return Struct
   */
  public function getStructValue()
  {
    return $this->structValue;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Value::class, 'Google_Service_Script_Value');
