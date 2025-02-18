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

class HivePartitioningOptions extends \Google\Collection
{
  protected $collection_key = 'fields';
  /**
   * @var string[]
   */
  public $fields;
  /**
   * @var string
   */
  public $mode;
  /**
   * @var bool
   */
  public $requirePartitionFilter;
  /**
   * @var string
   */
  public $sourceUriPrefix;

  /**
   * @param string[]
   */
  public function setFields($fields)
  {
    $this->fields = $fields;
  }
  /**
   * @return string[]
   */
  public function getFields()
  {
    return $this->fields;
  }
  /**
   * @param string
   */
  public function setMode($mode)
  {
    $this->mode = $mode;
  }
  /**
   * @return string
   */
  public function getMode()
  {
    return $this->mode;
  }
  /**
   * @param bool
   */
  public function setRequirePartitionFilter($requirePartitionFilter)
  {
    $this->requirePartitionFilter = $requirePartitionFilter;
  }
  /**
   * @return bool
   */
  public function getRequirePartitionFilter()
  {
    return $this->requirePartitionFilter;
  }
  /**
   * @param string
   */
  public function setSourceUriPrefix($sourceUriPrefix)
  {
    $this->sourceUriPrefix = $sourceUriPrefix;
  }
  /**
   * @return string
   */
  public function getSourceUriPrefix()
  {
    return $this->sourceUriPrefix;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(HivePartitioningOptions::class, 'Google_Service_Bigquery_HivePartitioningOptions');
