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

namespace Google\Service\CloudHealthcare;

class SchemaConfig extends \Google\Model
{
  protected $lastUpdatedPartitionConfigType = TimePartitioning::class;
  protected $lastUpdatedPartitionConfigDataType = '';
  /**
   * @var string
   */
  public $recursiveStructureDepth;
  /**
   * @var string
   */
  public $schemaType;

  /**
   * @param TimePartitioning
   */
  public function setLastUpdatedPartitionConfig(TimePartitioning $lastUpdatedPartitionConfig)
  {
    $this->lastUpdatedPartitionConfig = $lastUpdatedPartitionConfig;
  }
  /**
   * @return TimePartitioning
   */
  public function getLastUpdatedPartitionConfig()
  {
    return $this->lastUpdatedPartitionConfig;
  }
  /**
   * @param string
   */
  public function setRecursiveStructureDepth($recursiveStructureDepth)
  {
    $this->recursiveStructureDepth = $recursiveStructureDepth;
  }
  /**
   * @return string
   */
  public function getRecursiveStructureDepth()
  {
    return $this->recursiveStructureDepth;
  }
  /**
   * @param string
   */
  public function setSchemaType($schemaType)
  {
    $this->schemaType = $schemaType;
  }
  /**
   * @return string
   */
  public function getSchemaType()
  {
    return $this->schemaType;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SchemaConfig::class, 'Google_Service_CloudHealthcare_SchemaConfig');
