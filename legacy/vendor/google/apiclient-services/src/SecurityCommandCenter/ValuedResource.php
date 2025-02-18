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

namespace Google\Service\SecurityCommandCenter;

class ValuedResource extends \Google\Collection
{
  protected $collection_key = 'resourceValueConfigsUsed';
  /**
   * @var string
   */
  public $displayName;
  public $exposedScore;
  /**
   * @var string
   */
  public $name;
  /**
   * @var string
   */
  public $resource;
  /**
   * @var string
   */
  public $resourceType;
  /**
   * @var string
   */
  public $resourceValue;
  protected $resourceValueConfigsUsedType = ResourceValueConfigMetadata::class;
  protected $resourceValueConfigsUsedDataType = 'array';

  /**
   * @param string
   */
  public function setDisplayName($displayName)
  {
    $this->displayName = $displayName;
  }
  /**
   * @return string
   */
  public function getDisplayName()
  {
    return $this->displayName;
  }
  public function setExposedScore($exposedScore)
  {
    $this->exposedScore = $exposedScore;
  }
  public function getExposedScore()
  {
    return $this->exposedScore;
  }
  /**
   * @param string
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
  /**
   * @param string
   */
  public function setResource($resource)
  {
    $this->resource = $resource;
  }
  /**
   * @return string
   */
  public function getResource()
  {
    return $this->resource;
  }
  /**
   * @param string
   */
  public function setResourceType($resourceType)
  {
    $this->resourceType = $resourceType;
  }
  /**
   * @return string
   */
  public function getResourceType()
  {
    return $this->resourceType;
  }
  /**
   * @param string
   */
  public function setResourceValue($resourceValue)
  {
    $this->resourceValue = $resourceValue;
  }
  /**
   * @return string
   */
  public function getResourceValue()
  {
    return $this->resourceValue;
  }
  /**
   * @param ResourceValueConfigMetadata[]
   */
  public function setResourceValueConfigsUsed($resourceValueConfigsUsed)
  {
    $this->resourceValueConfigsUsed = $resourceValueConfigsUsed;
  }
  /**
   * @return ResourceValueConfigMetadata[]
   */
  public function getResourceValueConfigsUsed()
  {
    return $this->resourceValueConfigsUsed;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ValuedResource::class, 'Google_Service_SecurityCommandCenter_ValuedResource');
