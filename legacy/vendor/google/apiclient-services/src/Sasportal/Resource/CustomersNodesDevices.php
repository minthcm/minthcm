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

use Google\Service\Sasportal\SasPortalCreateSignedDeviceRequest;
use Google\Service\Sasportal\SasPortalDevice;
use Google\Service\Sasportal\SasPortalListDevicesResponse;

/**
 * The "devices" collection of methods.
 * Typical usage is:
 *  <code>
 *   $sasportalService = new Google\Service\Sasportal(...);
 *   $devices = $sasportalService->customers_nodes_devices;
 *  </code>
 */
class CustomersNodesDevices extends \Google\Service\Resource
{
  /**
   * Creates a device under a node or customer. (devices.create)
   *
   * @param string $parent Required. The name of the parent resource.
   * @param SasPortalDevice $postBody
   * @param array $optParams Optional parameters.
   * @return SasPortalDevice
   * @throws \Google\Service\Exception
   */
  public function create($parent, SasPortalDevice $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], SasPortalDevice::class);
  }
  /**
   * Creates a signed device under a node or customer. (devices.createSigned)
   *
   * @param string $parent Required. The name of the parent resource.
   * @param SasPortalCreateSignedDeviceRequest $postBody
   * @param array $optParams Optional parameters.
   * @return SasPortalDevice
   * @throws \Google\Service\Exception
   */
  public function createSigned($parent, SasPortalCreateSignedDeviceRequest $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('createSigned', [$params], SasPortalDevice::class);
  }
  /**
   * Lists devices under a node or customer. (devices.listCustomersNodesDevices)
   *
   * @param string $parent Required. The name of the parent resource.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter The filter expression. The filter should have one of
   * the following formats: "sn=123454" or "display_name=MyDevice". sn corresponds
   * to serial number of the device. The filter is case insensitive.
   * @opt_param int pageSize The maximum number of devices to return in the
   * response. If empty or zero, all devices will be listed. Must be in the range
   * [0, 1000].
   * @opt_param string pageToken A pagination token returned from a previous call
   * to ListDevices that indicates where this listing should continue from.
   * @return SasPortalListDevicesResponse
   * @throws \Google\Service\Exception
   */
  public function listCustomersNodesDevices($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], SasPortalListDevicesResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CustomersNodesDevices::class, 'Google_Service_Sasportal_Resource_CustomersNodesDevices');
