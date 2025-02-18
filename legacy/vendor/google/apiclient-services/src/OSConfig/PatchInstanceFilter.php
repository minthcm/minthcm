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

namespace Google\Service\OSConfig;

class PatchInstanceFilter extends \Google\Collection
{
  protected $collection_key = 'zones';
  /**
   * @var bool
   */
  public $all;
  protected $groupLabelsType = PatchInstanceFilterGroupLabel::class;
  protected $groupLabelsDataType = 'array';
  /**
   * @var string[]
   */
  public $instanceNamePrefixes;
  /**
   * @var string[]
   */
  public $instances;
  /**
   * @var string[]
   */
  public $zones;

  /**
   * @param bool
   */
  public function setAll($all)
  {
    $this->all = $all;
  }
  /**
   * @return bool
   */
  public function getAll()
  {
    return $this->all;
  }
  /**
   * @param PatchInstanceFilterGroupLabel[]
   */
  public function setGroupLabels($groupLabels)
  {
    $this->groupLabels = $groupLabels;
  }
  /**
   * @return PatchInstanceFilterGroupLabel[]
   */
  public function getGroupLabels()
  {
    return $this->groupLabels;
  }
  /**
   * @param string[]
   */
  public function setInstanceNamePrefixes($instanceNamePrefixes)
  {
    $this->instanceNamePrefixes = $instanceNamePrefixes;
  }
  /**
   * @return string[]
   */
  public function getInstanceNamePrefixes()
  {
    return $this->instanceNamePrefixes;
  }
  /**
   * @param string[]
   */
  public function setInstances($instances)
  {
    $this->instances = $instances;
  }
  /**
   * @return string[]
   */
  public function getInstances()
  {
    return $this->instances;
  }
  /**
   * @param string[]
   */
  public function setZones($zones)
  {
    $this->zones = $zones;
  }
  /**
   * @return string[]
   */
  public function getZones()
  {
    return $this->zones;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(PatchInstanceFilter::class, 'Google_Service_OSConfig_PatchInstanceFilter');
