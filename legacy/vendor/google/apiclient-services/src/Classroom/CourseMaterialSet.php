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

namespace Google\Service\Classroom;

class CourseMaterialSet extends \Google\Collection
{
  protected $collection_key = 'materials';
  protected $materialsType = CourseMaterial::class;
  protected $materialsDataType = 'array';
  /**
   * @var string
   */
  public $title;

  /**
   * @param CourseMaterial[]
   */
  public function setMaterials($materials)
  {
    $this->materials = $materials;
  }
  /**
   * @return CourseMaterial[]
   */
  public function getMaterials()
  {
    return $this->materials;
  }
  /**
   * @param string
   */
  public function setTitle($title)
  {
    $this->title = $title;
  }
  /**
   * @return string
   */
  public function getTitle()
  {
    return $this->title;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CourseMaterialSet::class, 'Google_Service_Classroom_CourseMaterialSet');
