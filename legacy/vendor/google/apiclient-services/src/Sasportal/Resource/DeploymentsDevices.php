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

namespace Google\Service\Sasportal\Resource;

use Google\Service\Sasportal\SasPortalDevice;
use Google\Service\Sasportal\SasPortalEmpty;
use Google\Service\Sasportal\SasPortalMoveDeviceRequest;
use Google\Service\Sasportal\SasPortalOperation;
use Google\Service\Sasportal\SasPortalSignDeviceRequest;
use Google\Service\Sasportal\SasPortalUpdateSignedDeviceRequest;

/**
 * The "devices" collection of methods.
 * Typical usage is:
 *  <code>
 *   $sasportalService = new Google\Service\Sasportal(...);
 *   $devices = $sasportalService->deployments_devices;
 *  </code>
 */
class DeploymentsDevices extends \Google\Service\Resource
{
  /**
   * Deletes a device. (devices.delete)
   *
   * @param string $name Required. The name of the device.
   * @param array $optParams Optional parameters.
   * @return SasPortalEmpty
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], SasPortalEmpty::class);
  }
  /**
   * Gets details about a device. (devices.get)
   *
   * @param string $name Required. The name of the device.
   * @param array $optParams Optional parameters.
   * @return SasPortalDevice
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], SasPortalDevice::class);
  }
  /**
   * Moves a device under another node or customer. (devices.move)
   *
   * @param string $name Required. The name of the device to move.
   * @param SasPortalMoveDeviceRequest $postBody
   * @param array $optParams Optional parameters.
   * @return SasPortalOperation
   * @throws \Google\Service\Exception
   */
  public function move($name, SasPortalMoveDeviceRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('move', [$params], SasPortalOperation::class);
  }
  /**
   * Updates a device. (devices.patch)
   *
   * @param string $name Output only. The resource path name.
   * @param SasPortalDevice $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string updateMask Fields to be updated.
   * @return SasPortalDevice
   * @throws \Google\Service\Exception
   */
  public function patch($name, SasPortalDevice $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], SasPortalDevice::class);
  }
  /**
   * Signs a device. (devices.signDevice)
   *
   * @param string $name Output only. The resource path name.
   * @param SasPortalSignDeviceRequest $postBody
   * @param array $optParams Optional parameters.
   * @return SasPortalEmpty
   * @throws \Google\Service\Exception
   */
  public function signDevice($name, SasPortalSignDeviceRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('signDevice', [$params], SasPortalEmpty::class);
  }
  /**
   * Updates a signed device. (devices.updateSigned)
   *
   * @param string $name Required. The name of the device to update.
   * @param SasPortalUpdateSignedDeviceRequest $postBody
   * @param array $optParams Optional parameters.
   * @return SasPortalDevice
   * @throws \Google\Service\Exception
   */
  public function updateSigned($name, SasPortalUpdateSignedDeviceRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('updateSigned', [$params], SasPortalDevice::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DeploymentsDevices::class, 'Google_Service_Sasportal_Resource_DeploymentsDevices');
