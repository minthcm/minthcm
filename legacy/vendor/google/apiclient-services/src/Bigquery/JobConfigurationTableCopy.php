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

class JobConfigurationTableCopy extends \Google\Collection
{
  protected $collection_key = 'sourceTables';
  /**
   * @var string
   */
  public $createDisposition;
  protected $destinationEncryptionConfigurationType = EncryptionConfiguration::class;
  protected $destinationEncryptionConfigurationDataType = '';
  /**
   * @var string
   */
  public $destinationExpirationTime;
  protected $destinationTableType = TableReference::class;
  protected $destinationTableDataType = '';
  /**
   * @var string
   */
  public $operationType;
  protected $sourceTableType = TableReference::class;
  protected $sourceTableDataType = '';
  protected $sourceTablesType = TableReference::class;
  protected $sourceTablesDataType = 'array';
  /**
   * @var string
   */
  public $writeDisposition;

  /**
   * @param string
   */
  public function setCreateDisposition($createDisposition)
  {
    $this->createDisposition = $createDisposition;
  }
  /**
   * @return string
   */
  public function getCreateDisposition()
  {
    return $this->createDisposition;
  }
  /**
   * @param EncryptionConfiguration
   */
  public function setDestinationEncryptionConfiguration(EncryptionConfiguration $destinationEncryptionConfiguration)
  {
    $this->destinationEncryptionConfiguration = $destinationEncryptionConfiguration;
  }
  /**
   * @return EncryptionConfiguration
   */
  public function getDestinationEncryptionConfiguration()
  {
    return $this->destinationEncryptionConfiguration;
  }
  /**
   * @param string
   */
  public function setDestinationExpirationTime($destinationExpirationTime)
  {
    $this->destinationExpirationTime = $destinationExpirationTime;
  }
  /**
   * @return string
   */
  public function getDestinationExpirationTime()
  {
    return $this->destinationExpirationTime;
  }
  /**
   * @param TableReference
   */
  public function setDestinationTable(TableReference $destinationTable)
  {
    $this->destinationTable = $destinationTable;
  }
  /**
   * @return TableReference
   */
  public function getDestinationTable()
  {
    return $this->destinationTable;
  }
  /**
   * @param string
   */
  public function setOperationType($operationType)
  {
    $this->operationType = $operationType;
  }
  /**
   * @return string
   */
  public function getOperationType()
  {
    return $this->operationType;
  }
  /**
   * @param TableReference
   */
  public function setSourceTable(TableReference $sourceTable)
  {
    $this->sourceTable = $sourceTable;
  }
  /**
   * @return TableReference
   */
  public function getSourceTable()
  {
    return $this->sourceTable;
  }
  /**
   * @param TableReference[]
   */
  public function setSourceTables($sourceTables)
  {
    $this->sourceTables = $sourceTables;
  }
  /**
   * @return TableReference[]
   */
  public function getSourceTables()
  {
    return $this->sourceTables;
  }
  /**
   * @param string
   */
  public function setWriteDisposition($writeDisposition)
  {
    $this->writeDisposition = $writeDisposition;
  }
  /**
   * @return string
   */
  public function getWriteDisposition()
  {
    return $this->writeDisposition;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(JobConfigurationTableCopy::class, 'Google_Service_Bigquery_JobConfigurationTableCopy');
