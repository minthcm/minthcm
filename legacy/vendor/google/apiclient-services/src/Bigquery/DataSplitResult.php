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

class DataSplitResult extends \Google\Model
{
  protected $evaluationTableType = TableReference::class;
  protected $evaluationTableDataType = '';
  protected $testTableType = TableReference::class;
  protected $testTableDataType = '';
  protected $trainingTableType = TableReference::class;
  protected $trainingTableDataType = '';

  /**
   * @param TableReference
   */
  public function setEvaluationTable(TableReference $evaluationTable)
  {
    $this->evaluationTable = $evaluationTable;
  }
  /**
   * @return TableReference
   */
  public function getEvaluationTable()
  {
    return $this->evaluationTable;
  }
  /**
   * @param TableReference
   */
  public function setTestTable(TableReference $testTable)
  {
    $this->testTable = $testTable;
  }
  /**
   * @return TableReference
   */
  public function getTestTable()
  {
    return $this->testTable;
  }
  /**
   * @param TableReference
   */
  public function setTrainingTable(TableReference $trainingTable)
  {
    $this->trainingTable = $trainingTable;
  }
  /**
   * @return TableReference
   */
  public function getTrainingTable()
  {
    return $this->trainingTable;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DataSplitResult::class, 'Google_Service_Bigquery_DataSplitResult');
