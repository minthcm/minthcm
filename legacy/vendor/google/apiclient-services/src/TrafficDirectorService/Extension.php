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

namespace Google\Service\TrafficDirectorService;

class Extension extends \Google\Collection
{
  protected $collection_key = 'typeUrls';
  /**
   * @var string
   */
  public $category;
  /**
   * @var bool
   */
  public $disabled;
  /**
   * @var string
   */
  public $name;
  /**
   * @var string
   */
  public $typeDescriptor;
  /**
   * @var string[]
   */
  public $typeUrls;
  protected $versionType = BuildVersion::class;
  protected $versionDataType = '';

  /**
   * @param string
   */
  public function setCategory($category)
  {
    $this->category = $category;
  }
  /**
   * @return string
   */
  public function getCategory()
  {
    return $this->category;
  }
  /**
   * @param bool
   */
  public function setDisabled($disabled)
  {
    $this->disabled = $disabled;
  }
  /**
   * @return bool
   */
  public function getDisabled()
  {
    return $this->disabled;
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
  public function setTypeDescriptor($typeDescriptor)
  {
    $this->typeDescriptor = $typeDescriptor;
  }
  /**
   * @return string
   */
  public function getTypeDescriptor()
  {
    return $this->typeDescriptor;
  }
  /**
   * @param string[]
   */
  public function setTypeUrls($typeUrls)
  {
    $this->typeUrls = $typeUrls;
  }
  /**
   * @return string[]
   */
  public function getTypeUrls()
  {
    return $this->typeUrls;
  }
  /**
   * @param BuildVersion
   */
  public function setVersion(BuildVersion $version)
  {
    $this->version = $version;
  }
  /**
   * @return BuildVersion
   */
  public function getVersion()
  {
    return $this->version;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Extension::class, 'Google_Service_TrafficDirectorService_Extension');
