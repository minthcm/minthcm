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

class SdkHarnessContainerImage extends \Google\Collection
{
  protected $collection_key = 'capabilities';
  /**
   * @var string[]
   */
  public $capabilities;
  /**
   * @var string
   */
  public $containerImage;
  /**
   * @var string
   */
  public $environmentId;
  /**
   * @var bool
   */
  public $useSingleCorePerContainer;

  /**
   * @param string[]
   */
  public function setCapabilities($capabilities)
  {
    $this->capabilities = $capabilities;
  }
  /**
   * @return string[]
   */
  public function getCapabilities()
  {
    return $this->capabilities;
  }
  /**
   * @param string
   */
  public function setContainerImage($containerImage)
  {
    $this->containerImage = $containerImage;
  }
  /**
   * @return string
   */
  public function getContainerImage()
  {
    return $this->containerImage;
  }
  /**
   * @param string
   */
  public function setEnvironmentId($environmentId)
  {
    $this->environmentId = $environmentId;
  }
  /**
   * @return string
   */
  public function getEnvironmentId()
  {
    return $this->environmentId;
  }
  /**
   * @param bool
   */
  public function setUseSingleCorePerContainer($useSingleCorePerContainer)
  {
    $this->useSingleCorePerContainer = $useSingleCorePerContainer;
  }
  /**
   * @return bool
   */
  public function getUseSingleCorePerContainer()
  {
    return $this->useSingleCorePerContainer;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SdkHarnessContainerImage::class, 'Google_Service_Dataflow_SdkHarnessContainerImage');
