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

namespace Google\Service\ChromeManagement;

class GoogleChromeManagementV1CpuInfo extends \Google\Model
{
  /**
   * @var string
   */
  public $architecture;
  /**
   * @var bool
   */
  public $keylockerConfigured;
  /**
   * @var bool
   */
  public $keylockerSupported;
  /**
   * @var int
   */
  public $maxClockSpeed;
  /**
   * @var string
   */
  public $model;

  /**
   * @param string
   */
  public function setArchitecture($architecture)
  {
    $this->architecture = $architecture;
  }
  /**
   * @return string
   */
  public function getArchitecture()
  {
    return $this->architecture;
  }
  /**
   * @param bool
   */
  public function setKeylockerConfigured($keylockerConfigured)
  {
    $this->keylockerConfigured = $keylockerConfigured;
  }
  /**
   * @return bool
   */
  public function getKeylockerConfigured()
  {
    return $this->keylockerConfigured;
  }
  /**
   * @param bool
   */
  public function setKeylockerSupported($keylockerSupported)
  {
    $this->keylockerSupported = $keylockerSupported;
  }
  /**
   * @return bool
   */
  public function getKeylockerSupported()
  {
    return $this->keylockerSupported;
  }
  /**
   * @param int
   */
  public function setMaxClockSpeed($maxClockSpeed)
  {
    $this->maxClockSpeed = $maxClockSpeed;
  }
  /**
   * @return int
   */
  public function getMaxClockSpeed()
  {
    return $this->maxClockSpeed;
  }
  /**
   * @param string
   */
  public function setModel($model)
  {
    $this->model = $model;
  }
  /**
   * @return string
   */
  public function getModel()
  {
    return $this->model;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleChromeManagementV1CpuInfo::class, 'Google_Service_ChromeManagement_GoogleChromeManagementV1CpuInfo');
