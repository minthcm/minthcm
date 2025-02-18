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

class GoogleChromeManagementV1GraphicsAdapterInfo extends \Google\Model
{
  /**
   * @var string
   */
  public $adapter;
  /**
   * @var string
   */
  public $deviceId;
  /**
   * @var string
   */
  public $driverVersion;

  /**
   * @param string
   */
  public function setAdapter($adapter)
  {
    $this->adapter = $adapter;
  }
  /**
   * @return string
   */
  public function getAdapter()
  {
    return $this->adapter;
  }
  /**
   * @param string
   */
  public function setDeviceId($deviceId)
  {
    $this->deviceId = $deviceId;
  }
  /**
   * @return string
   */
  public function getDeviceId()
  {
    return $this->deviceId;
  }
  /**
   * @param string
   */
  public function setDriverVersion($driverVersion)
  {
    $this->driverVersion = $driverVersion;
  }
  /**
   * @return string
   */
  public function getDriverVersion()
  {
    return $this->driverVersion;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleChromeManagementV1GraphicsAdapterInfo::class, 'Google_Service_ChromeManagement_GoogleChromeManagementV1GraphicsAdapterInfo');
