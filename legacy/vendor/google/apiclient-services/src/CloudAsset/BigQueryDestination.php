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

namespace Google\Service\CloudAsset;

class BigQueryDestination extends \Google\Model
{
  /**
   * @var string
   */
  public $dataset;
  /**
   * @var bool
   */
  public $force;
  protected $partitionSpecType = PartitionSpec::class;
  protected $partitionSpecDataType = '';
  /**
   * @var bool
   */
  public $separateTablesPerAssetType;
  /**
   * @var string
   */
  public $table;

  /**
   * @param string
   */
  public function setDataset($dataset)
  {
    $this->dataset = $dataset;
  }
  /**
   * @return string
   */
  public function getDataset()
  {
    return $this->dataset;
  }
  /**
   * @param bool
   */
  public function setForce($force)
  {
    $this->force = $force;
  }
  /**
   * @return bool
   */
  public function getForce()
  {
    return $this->force;
  }
  /**
   * @param PartitionSpec
   */
  public function setPartitionSpec(PartitionSpec $partitionSpec)
  {
    $this->partitionSpec = $partitionSpec;
  }
  /**
   * @return PartitionSpec
   */
  public function getPartitionSpec()
  {
    return $this->partitionSpec;
  }
  /**
   * @param bool
   */
  public function setSeparateTablesPerAssetType($separateTablesPerAssetType)
  {
    $this->separateTablesPerAssetType = $separateTablesPerAssetType;
  }
  /**
   * @return bool
   */
  public function getSeparateTablesPerAssetType()
  {
    return $this->separateTablesPerAssetType;
  }
  /**
   * @param string
   */
  public function setTable($table)
  {
    $this->table = $table;
  }
  /**
   * @return string
   */
  public function getTable()
  {
    return $this->table;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(BigQueryDestination::class, 'Google_Service_CloudAsset_BigQueryDestination');
