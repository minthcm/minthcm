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

namespace Google\Service\AlertCenter;

class Entity extends \Google\Collection
{
  protected $collection_key = 'values';
  /**
   * @var string
   */
  public $link;
  /**
   * @var string
   */
  public $name;
  /**
   * @var string[]
   */
  public $values;

  /**
   * @param string
   */
  public function setLink($link)
  {
    $this->link = $link;
  }
  /**
   * @return string
   */
  public function getLink()
  {
    return $this->link;
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
   * @param string[]
   */
  public function setValues($values)
  {
    $this->values = $values;
  }
  /**
   * @return string[]
   */
  public function getValues()
  {
    return $this->values;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Entity::class, 'Google_Service_AlertCenter_Entity');
