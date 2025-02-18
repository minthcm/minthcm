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

namespace Google\Service\MigrationCenterAPI;

class GuestOsDetails extends \Google\Model
{
  protected $configType = GuestConfigDetails::class;
  protected $configDataType = '';
  /**
   * @var string
   */
  public $family;
  /**
   * @var string
   */
  public $osName;
  protected $runtimeType = GuestRuntimeDetails::class;
  protected $runtimeDataType = '';
  /**
   * @var string
   */
  public $version;

  /**
   * @param GuestConfigDetails
   */
  public function setConfig(GuestConfigDetails $config)
  {
    $this->config = $config;
  }
  /**
   * @return GuestConfigDetails
   */
  public function getConfig()
  {
    return $this->config;
  }
  /**
   * @param string
   */
  public function setFamily($family)
  {
    $this->family = $family;
  }
  /**
   * @return string
   */
  public function getFamily()
  {
    return $this->family;
  }
  /**
   * @param string
   */
  public function setOsName($osName)
  {
    $this->osName = $osName;
  }
  /**
   * @return string
   */
  public function getOsName()
  {
    return $this->osName;
  }
  /**
   * @param GuestRuntimeDetails
   */
  public function setRuntime(GuestRuntimeDetails $runtime)
  {
    $this->runtime = $runtime;
  }
  /**
   * @return GuestRuntimeDetails
   */
  public function getRuntime()
  {
    return $this->runtime;
  }
  /**
   * @param string
   */
  public function setVersion($version)
  {
    $this->version = $version;
  }
  /**
   * @return string
   */
  public function getVersion()
  {
    return $this->version;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GuestOsDetails::class, 'Google_Service_MigrationCenterAPI_GuestOsDetails');
