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

namespace Google\Service\BigLakeService;

class HiveTableOptions extends \Google\Model
{
  /**
   * @var string[]
   */
  public $parameters;
  protected $storageDescriptorType = StorageDescriptor::class;
  protected $storageDescriptorDataType = '';
  /**
   * @var string
   */
  public $tableType;

  /**
   * @param string[]
   */
  public function setParameters($parameters)
  {
    $this->parameters = $parameters;
  }
  /**
   * @return string[]
   */
  public function getParameters()
  {
    return $this->parameters;
  }
  /**
   * @param StorageDescriptor
   */
  public function setStorageDescriptor(StorageDescriptor $storageDescriptor)
  {
    $this->storageDescriptor = $storageDescriptor;
  }
  /**
   * @return StorageDescriptor
   */
  public function getStorageDescriptor()
  {
    return $this->storageDescriptor;
  }
  /**
   * @param string
   */
  public function setTableType($tableType)
  {
    $this->tableType = $tableType;
  }
  /**
   * @return string
   */
  public function getTableType()
  {
    return $this->tableType;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(HiveTableOptions::class, 'Google_Service_BigLakeService_HiveTableOptions');
