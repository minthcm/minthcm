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

class GoogleChromeManagementV1GraphicsInfo extends \Google\Collection
{
  protected $collection_key = 'displayDevices';
  protected $adapterInfoType = GoogleChromeManagementV1GraphicsAdapterInfo::class;
  protected $adapterInfoDataType = '';
  protected $displayDevicesType = GoogleChromeManagementV1DisplayDevice::class;
  protected $displayDevicesDataType = 'array';
  /**
   * @var bool
   */
  public $eprivacySupported;
  protected $touchScreenInfoType = GoogleChromeManagementV1TouchScreenInfo::class;
  protected $touchScreenInfoDataType = '';

  /**
   * @param GoogleChromeManagementV1GraphicsAdapterInfo
   */
  public function setAdapterInfo(GoogleChromeManagementV1GraphicsAdapterInfo $adapterInfo)
  {
    $this->adapterInfo = $adapterInfo;
  }
  /**
   * @return GoogleChromeManagementV1GraphicsAdapterInfo
   */
  public function getAdapterInfo()
  {
    return $this->adapterInfo;
  }
  /**
   * @param GoogleChromeManagementV1DisplayDevice[]
   */
  public function setDisplayDevices($displayDevices)
  {
    $this->displayDevices = $displayDevices;
  }
  /**
   * @return GoogleChromeManagementV1DisplayDevice[]
   */
  public function getDisplayDevices()
  {
    return $this->displayDevices;
  }
  /**
   * @param bool
   */
  public function setEprivacySupported($eprivacySupported)
  {
    $this->eprivacySupported = $eprivacySupported;
  }
  /**
   * @return bool
   */
  public function getEprivacySupported()
  {
    return $this->eprivacySupported;
  }
  /**
   * @param GoogleChromeManagementV1TouchScreenInfo
   */
  public function setTouchScreenInfo(GoogleChromeManagementV1TouchScreenInfo $touchScreenInfo)
  {
    $this->touchScreenInfo = $touchScreenInfo;
  }
  /**
   * @return GoogleChromeManagementV1TouchScreenInfo
   */
  public function getTouchScreenInfo()
  {
    return $this->touchScreenInfo;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleChromeManagementV1GraphicsInfo::class, 'Google_Service_ChromeManagement_GoogleChromeManagementV1GraphicsInfo');
