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

namespace Google\Service\Sheets;

class PivotFilterCriteria extends \Google\Collection
{
  protected $collection_key = 'visibleValues';
  protected $conditionType = BooleanCondition::class;
  protected $conditionDataType = '';
  /**
   * @var bool
   */
  public $visibleByDefault;
  /**
   * @var string[]
   */
  public $visibleValues;

  /**
   * @param BooleanCondition
   */
  public function setCondition(BooleanCondition $condition)
  {
    $this->condition = $condition;
  }
  /**
   * @return BooleanCondition
   */
  public function getCondition()
  {
    return $this->condition;
  }
  /**
   * @param bool
   */
  public function setVisibleByDefault($visibleByDefault)
  {
    $this->visibleByDefault = $visibleByDefault;
  }
  /**
   * @return bool
   */
  public function getVisibleByDefault()
  {
    return $this->visibleByDefault;
  }
  /**
   * @param string[]
   */
  public function setVisibleValues($visibleValues)
  {
    $this->visibleValues = $visibleValues;
  }
  /**
   * @return string[]
   */
  public function getVisibleValues()
  {
    return $this->visibleValues;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(PivotFilterCriteria::class, 'Google_Service_Sheets_PivotFilterCriteria');
