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

namespace Google\Service\CloudSearch;

class ObjectDisplayOptions extends \Google\Collection
{
  protected $collection_key = 'metalines';
  protected $metalinesType = Metaline::class;
  protected $metalinesDataType = 'array';
  /**
   * @var string
   */
  public $objectDisplayLabel;

  /**
   * @param Metaline[]
   */
  public function setMetalines($metalines)
  {
    $this->metalines = $metalines;
  }
  /**
   * @return Metaline[]
   */
  public function getMetalines()
  {
    return $this->metalines;
  }
  /**
   * @param string
   */
  public function setObjectDisplayLabel($objectDisplayLabel)
  {
    $this->objectDisplayLabel = $objectDisplayLabel;
  }
  /**
   * @return string
   */
  public function getObjectDisplayLabel()
  {
    return $this->objectDisplayLabel;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ObjectDisplayOptions::class, 'Google_Service_CloudSearch_ObjectDisplayOptions');
