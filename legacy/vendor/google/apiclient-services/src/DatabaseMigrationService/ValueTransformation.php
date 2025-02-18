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

namespace Google\Service\DatabaseMigrationService;

class ValueTransformation extends \Google\Model
{
  protected $applyHashType = ApplyHash::class;
  protected $applyHashDataType = '';
  protected $assignMaxValueType = DatamigrationEmpty::class;
  protected $assignMaxValueDataType = '';
  protected $assignMinValueType = DatamigrationEmpty::class;
  protected $assignMinValueDataType = '';
  protected $assignNullType = DatamigrationEmpty::class;
  protected $assignNullDataType = '';
  protected $assignSpecificValueType = AssignSpecificValue::class;
  protected $assignSpecificValueDataType = '';
  protected $doubleComparisonType = DoubleComparisonFilter::class;
  protected $doubleComparisonDataType = '';
  protected $intComparisonType = IntComparisonFilter::class;
  protected $intComparisonDataType = '';
  protected $isNullType = DatamigrationEmpty::class;
  protected $isNullDataType = '';
  protected $roundScaleType = RoundToScale::class;
  protected $roundScaleDataType = '';
  protected $valueListType = ValueListFilter::class;
  protected $valueListDataType = '';

  /**
   * @param ApplyHash
   */
  public function setApplyHash(ApplyHash $applyHash)
  {
    $this->applyHash = $applyHash;
  }
  /**
   * @return ApplyHash
   */
  public function getApplyHash()
  {
    return $this->applyHash;
  }
  /**
   * @param DatamigrationEmpty
   */
  public function setAssignMaxValue(DatamigrationEmpty $assignMaxValue)
  {
    $this->assignMaxValue = $assignMaxValue;
  }
  /**
   * @return DatamigrationEmpty
   */
  public function getAssignMaxValue()
  {
    return $this->assignMaxValue;
  }
  /**
   * @param DatamigrationEmpty
   */
  public function setAssignMinValue(DatamigrationEmpty $assignMinValue)
  {
    $this->assignMinValue = $assignMinValue;
  }
  /**
   * @return DatamigrationEmpty
   */
  public function getAssignMinValue()
  {
    return $this->assignMinValue;
  }
  /**
   * @param DatamigrationEmpty
   */
  public function setAssignNull(DatamigrationEmpty $assignNull)
  {
    $this->assignNull = $assignNull;
  }
  /**
   * @return DatamigrationEmpty
   */
  public function getAssignNull()
  {
    return $this->assignNull;
  }
  /**
   * @param AssignSpecificValue
   */
  public function setAssignSpecificValue(AssignSpecificValue $assignSpecificValue)
  {
    $this->assignSpecificValue = $assignSpecificValue;
  }
  /**
   * @return AssignSpecificValue
   */
  public function getAssignSpecificValue()
  {
    return $this->assignSpecificValue;
  }
  /**
   * @param DoubleComparisonFilter
   */
  public function setDoubleComparison(DoubleComparisonFilter $doubleComparison)
  {
    $this->doubleComparison = $doubleComparison;
  }
  /**
   * @return DoubleComparisonFilter
   */
  public function getDoubleComparison()
  {
    return $this->doubleComparison;
  }
  /**
   * @param IntComparisonFilter
   */
  public function setIntComparison(IntComparisonFilter $intComparison)
  {
    $this->intComparison = $intComparison;
  }
  /**
   * @return IntComparisonFilter
   */
  public function getIntComparison()
  {
    return $this->intComparison;
  }
  /**
   * @param DatamigrationEmpty
   */
  public function setIsNull(DatamigrationEmpty $isNull)
  {
    $this->isNull = $isNull;
  }
  /**
   * @return DatamigrationEmpty
   */
  public function getIsNull()
  {
    return $this->isNull;
  }
  /**
   * @param RoundToScale
   */
  public function setRoundScale(RoundToScale $roundScale)
  {
    $this->roundScale = $roundScale;
  }
  /**
   * @return RoundToScale
   */
  public function getRoundScale()
  {
    return $this->roundScale;
  }
  /**
   * @param ValueListFilter
   */
  public function setValueList(ValueListFilter $valueList)
  {
    $this->valueList = $valueList;
  }
  /**
   * @return ValueListFilter
   */
  public function getValueList()
  {
    return $this->valueList;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ValueTransformation::class, 'Google_Service_DatabaseMigrationService_ValueTransformation');
