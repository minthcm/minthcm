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

class GoogleIdentityAccesscontextmanagerV1IngressTo extends \Google\Collection
{
  protected $collection_key = 'resources';
  protected $operationsType = GoogleIdentityAccesscontextmanagerV1ApiOperation::class;
  protected $operationsDataType = 'array';
  /**
   * @var string[]
   */
  public $resources;

  /**
   * @param GoogleIdentityAccesscontextmanagerV1ApiOperation[]
   */
  public function setOperations($operations)
  {
    $this->operations = $operations;
  }
  /**
   * @return GoogleIdentityAccesscontextmanagerV1ApiOperation[]
   */
  public function getOperations()
  {
    return $this->operations;
  }
  /**
   * @param string[]
   */
  public function setResources($resources)
  {
    $this->resources = $resources;
  }
  /**
   * @return string[]
   */
  public function getResources()
  {
    return $this->resources;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleIdentityAccesscontextmanagerV1IngressTo::class, 'Google_Service_CloudAsset_GoogleIdentityAccesscontextmanagerV1IngressTo');
