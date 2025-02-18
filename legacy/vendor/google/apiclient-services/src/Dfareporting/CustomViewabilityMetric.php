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

namespace Google\Service\Dfareporting;

class CustomViewabilityMetric extends \Google\Model
{
  protected $configurationType = CustomViewabilityMetricConfiguration::class;
  protected $configurationDataType = '';
  /**
   * @var string
   */
  public $id;
  /**
   * @var string
   */
  public $name;

  /**
   * @param CustomViewabilityMetricConfiguration
   */
  public function setConfiguration(CustomViewabilityMetricConfiguration $configuration)
  {
    $this->configuration = $configuration;
  }
  /**
   * @return CustomViewabilityMetricConfiguration
   */
  public function getConfiguration()
  {
    return $this->configuration;
  }
  /**
   * @param string
   */
  public function setId($id)
  {
    $this->id = $id;
  }
  /**
   * @return string
   */
  public function getId()
  {
    return $this->id;
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
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CustomViewabilityMetric::class, 'Google_Service_Dfareporting_CustomViewabilityMetric');
