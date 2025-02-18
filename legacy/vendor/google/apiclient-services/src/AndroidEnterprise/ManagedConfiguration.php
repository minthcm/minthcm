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

namespace Google\Service\AndroidEnterprise;

class ManagedConfiguration extends \Google\Collection
{
  protected $collection_key = 'managedProperty';
  protected $configurationVariablesType = ConfigurationVariables::class;
  protected $configurationVariablesDataType = '';
  /**
   * @var string
   */
  public $kind;
  protected $managedPropertyType = ManagedProperty::class;
  protected $managedPropertyDataType = 'array';
  /**
   * @var string
   */
  public $productId;

  /**
   * @param ConfigurationVariables
   */
  public function setConfigurationVariables(ConfigurationVariables $configurationVariables)
  {
    $this->configurationVariables = $configurationVariables;
  }
  /**
   * @return ConfigurationVariables
   */
  public function getConfigurationVariables()
  {
    return $this->configurationVariables;
  }
  /**
   * @param string
   */
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  /**
   * @return string
   */
  public function getKind()
  {
    return $this->kind;
  }
  /**
   * @param ManagedProperty[]
   */
  public function setManagedProperty($managedProperty)
  {
    $this->managedProperty = $managedProperty;
  }
  /**
   * @return ManagedProperty[]
   */
  public function getManagedProperty()
  {
    return $this->managedProperty;
  }
  /**
   * @param string
   */
  public function setProductId($productId)
  {
    $this->productId = $productId;
  }
  /**
   * @return string
   */
  public function getProductId()
  {
    return $this->productId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ManagedConfiguration::class, 'Google_Service_AndroidEnterprise_ManagedConfiguration');
