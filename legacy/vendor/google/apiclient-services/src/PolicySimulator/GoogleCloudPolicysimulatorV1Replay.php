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

namespace Google\Service\PolicySimulator;

class GoogleCloudPolicysimulatorV1Replay extends \Google\Model
{
  protected $configType = GoogleCloudPolicysimulatorV1ReplayConfig::class;
  protected $configDataType = '';
  /**
   * @var string
   */
  public $name;
  protected $resultsSummaryType = GoogleCloudPolicysimulatorV1ReplayResultsSummary::class;
  protected $resultsSummaryDataType = '';
  /**
   * @var string
   */
  public $state;

  /**
   * @param GoogleCloudPolicysimulatorV1ReplayConfig
   */
  public function setConfig(GoogleCloudPolicysimulatorV1ReplayConfig $config)
  {
    $this->config = $config;
  }
  /**
   * @return GoogleCloudPolicysimulatorV1ReplayConfig
   */
  public function getConfig()
  {
    return $this->config;
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
   * @param GoogleCloudPolicysimulatorV1ReplayResultsSummary
   */
  public function setResultsSummary(GoogleCloudPolicysimulatorV1ReplayResultsSummary $resultsSummary)
  {
    $this->resultsSummary = $resultsSummary;
  }
  /**
   * @return GoogleCloudPolicysimulatorV1ReplayResultsSummary
   */
  public function getResultsSummary()
  {
    return $this->resultsSummary;
  }
  /**
   * @param string
   */
  public function setState($state)
  {
    $this->state = $state;
  }
  /**
   * @return string
   */
  public function getState()
  {
    return $this->state;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudPolicysimulatorV1Replay::class, 'Google_Service_PolicySimulator_GoogleCloudPolicysimulatorV1Replay');
