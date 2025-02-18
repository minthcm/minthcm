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

namespace Google\Service\Dataflow;

class DataDiskAssignment extends \Google\Collection
{
  protected $collection_key = 'dataDisks';
  /**
   * @var string[]
   */
  public $dataDisks;
  /**
   * @var string
   */
  public $vmInstance;

  /**
   * @param string[]
   */
  public function setDataDisks($dataDisks)
  {
    $this->dataDisks = $dataDisks;
  }
  /**
   * @return string[]
   */
  public function getDataDisks()
  {
    return $this->dataDisks;
  }
  /**
   * @param string
   */
  public function setVmInstance($vmInstance)
  {
    $this->vmInstance = $vmInstance;
  }
  /**
   * @return string
   */
  public function getVmInstance()
  {
    return $this->vmInstance;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DataDiskAssignment::class, 'Google_Service_Dataflow_DataDiskAssignment');
