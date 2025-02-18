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

class AptSettings extends \Google\Collection
{
  protected $collection_key = 'exclusivePackages';
  /**
   * @var string[]
   */
  public $excludes;
  /**
   * @var string[]
   */
  public $exclusivePackages;
  /**
   * @var string
   */
  public $type;

  /**
   * @param string[]
   */
  public function setExcludes($excludes)
  {
    $this->excludes = $excludes;
  }
  /**
   * @return string[]
   */
  public function getExcludes()
  {
    return $this->excludes;
  }
  /**
   * @param string[]
   */
  public function setExclusivePackages($exclusivePackages)
  {
    $this->exclusivePackages = $exclusivePackages;
  }
  /**
   * @return string[]
   */
  public function getExclusivePackages()
  {
    return $this->exclusivePackages;
  }
  /**
   * @param string
   */
  public function setType($type)
  {
    $this->type = $type;
  }
  /**
   * @return string
   */
  public function getType()
  {
    return $this->type;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AptSettings::class, 'Google_Service_OSConfig_AptSettings');
