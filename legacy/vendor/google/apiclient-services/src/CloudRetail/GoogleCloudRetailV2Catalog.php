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

namespace Google\Service\CloudRetail;

class GoogleCloudRetailV2Catalog extends \Google\Model
{
  /**
   * @var string
   */
  public $displayName;
  /**
   * @var string
   */
  public $name;
  protected $productLevelConfigType = GoogleCloudRetailV2ProductLevelConfig::class;
  protected $productLevelConfigDataType = '';

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
   * @param GoogleCloudRetailV2ProductLevelConfig
   */
  public function setProductLevelConfig(GoogleCloudRetailV2ProductLevelConfig $productLevelConfig)
  {
    $this->productLevelConfig = $productLevelConfig;
  }
  /**
   * @return GoogleCloudRetailV2ProductLevelConfig
   */
  public function getProductLevelConfig()
  {
    return $this->productLevelConfig;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudRetailV2Catalog::class, 'Google_Service_CloudRetail_GoogleCloudRetailV2Catalog');
