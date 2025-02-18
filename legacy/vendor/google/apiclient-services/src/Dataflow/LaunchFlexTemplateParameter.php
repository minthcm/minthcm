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

namespace Google\Service\Dataflow;

class LaunchFlexTemplateParameter extends \Google\Model
{
  protected $containerSpecType = ContainerSpec::class;
  protected $containerSpecDataType = '';
  /**
   * @var string
   */
  public $containerSpecGcsPath;
  protected $environmentType = FlexTemplateRuntimeEnvironment::class;
  protected $environmentDataType = '';
  /**
   * @var string
   */
  public $jobName;
  /**
   * @var string[]
   */
  public $launchOptions;
  /**
   * @var string[]
   */
  public $parameters;
  /**
   * @var string[]
   */
  public $transformNameMappings;
  /**
   * @var bool
   */
  public $update;

  /**
   * @param ContainerSpec
   */
  public function setContainerSpec(ContainerSpec $containerSpec)
  {
    $this->containerSpec = $containerSpec;
  }
  /**
   * @return ContainerSpec
   */
  public function getContainerSpec()
  {
    return $this->containerSpec;
  }
  /**
   * @param string
   */
  public function setContainerSpecGcsPath($containerSpecGcsPath)
  {
    $this->containerSpecGcsPath = $containerSpecGcsPath;
  }
  /**
   * @return string
   */
  public function getContainerSpecGcsPath()
  {
    return $this->containerSpecGcsPath;
  }
  /**
   * @param FlexTemplateRuntimeEnvironment
   */
  public function setEnvironment(FlexTemplateRuntimeEnvironment $environment)
  {
    $this->environment = $environment;
  }
  /**
   * @return FlexTemplateRuntimeEnvironment
   */
  public function getEnvironment()
  {
    return $this->environment;
  }
  /**
   * @param string
   */
  public function setJobName($jobName)
  {
    $this->jobName = $jobName;
  }
  /**
   * @return string
   */
  public function getJobName()
  {
    return $this->jobName;
  }
  /**
   * @param string[]
   */
  public function setLaunchOptions($launchOptions)
  {
    $this->launchOptions = $launchOptions;
  }
  /**
   * @return string[]
   */
  public function getLaunchOptions()
  {
    return $this->launchOptions;
  }
  /**
   * @param string[]
   */
  public function setParameters($parameters)
  {
    $this->parameters = $parameters;
  }
  /**
   * @return string[]
   */
  public function getParameters()
  {
    return $this->parameters;
  }
  /**
   * @param string[]
   */
  public function setTransformNameMappings($transformNameMappings)
  {
    $this->transformNameMappings = $transformNameMappings;
  }
  /**
   * @return string[]
   */
  public function getTransformNameMappings()
  {
    return $this->transformNameMappings;
  }
  /**
   * @param bool
   */
  public function setUpdate($update)
  {
    $this->update = $update;
  }
  /**
   * @return bool
   */
  public function getUpdate()
  {
    return $this->update;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(LaunchFlexTemplateParameter::class, 'Google_Service_Dataflow_LaunchFlexTemplateParameter');
