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

namespace Google\Service\Bigquery;

class QueryParameterValue extends \Google\Collection
{
  protected $collection_key = 'arrayValues';
  protected $arrayValuesType = QueryParameterValue::class;
  protected $arrayValuesDataType = 'array';
  protected $rangeValueType = RangeValue::class;
  protected $rangeValueDataType = '';
  protected $structValuesType = QueryParameterValue::class;
  protected $structValuesDataType = 'map';
  /**
   * @var string
   */
  public $value;

  /**
   * @param QueryParameterValue[]
   */
  public function setArrayValues($arrayValues)
  {
    $this->arrayValues = $arrayValues;
  }
  /**
   * @return QueryParameterValue[]
   */
  public function getArrayValues()
  {
    return $this->arrayValues;
  }
  /**
   * @param RangeValue
   */
  public function setRangeValue(RangeValue $rangeValue)
  {
    $this->rangeValue = $rangeValue;
  }
  /**
   * @return RangeValue
   */
  public function getRangeValue()
  {
    return $this->rangeValue;
  }
  /**
   * @param QueryParameterValue[]
   */
  public function setStructValues($structValues)
  {
    $this->structValues = $structValues;
  }
  /**
   * @return QueryParameterValue[]
   */
  public function getStructValues()
  {
    return $this->structValues;
  }
  /**
   * @param string
   */
  public function setValue($value)
  {
    $this->value = $value;
  }
  /**
   * @return string
   */
  public function getValue()
  {
    return $this->value;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(QueryParameterValue::class, 'Google_Service_Bigquery_QueryParameterValue');
