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

namespace Google\Service\CloudIdentity\Resource;

use Google\Service\CloudIdentity\GoogleAppsCloudidentityDevicesV1ClientState;
use Google\Service\CloudIdentity\GoogleAppsCloudidentityDevicesV1ListClientStatesResponse;
use Google\Service\CloudIdentity\Operation;

/**
 * The "clientStates" collection of methods.
 * Typical usage is:
 *  <code>
 *   $cloudidentityService = new Google\Service\CloudIdentity(...);
 *   $clientStates = $cloudidentityService->devices_deviceUsers_clientStates;
 *  </code>
 */
class DevicesDeviceUsersClientStates extends \Google\Service\Resource
{
  /**
   * Gets the client state for the device user (clientStates.get)
   *
   * @param string $name Required. [Resource
   * name](https://cloud.google.com/apis/design/resource_names) of the ClientState
   * in format:
   * `devices/{device}/deviceUsers/{device_user}/clientStates/{partner}`, where
   * `device` is the unique ID assigned to the Device, `device_user` is the unique
   * ID assigned to the User and `partner` identifies the partner storing the
   * data. To get the client state for devices belonging to your own organization,
   * the `partnerId` is in the format: `customerId-*anystring*`. Where the
   * `customerId` is your organization's customer ID and `anystring` is any
   * suffix. This suffix is used in setting up Custom Access Levels in Context-
   * Aware Access. You may use `my_customer` instead of the customer ID for
   * devices managed by your own organization. You may specify `-` in place of the
   * `{device}`, so the ClientState resource name can be:
   * `devices/-/deviceUsers/{device_user_resource}/clientStates/{partner}`.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string customer Optional. [Resource
   * name](https://cloud.google.com/apis/design/resource_names) of the customer.
   * If you're using this API for your own organization, use
   * `customers/my_customer` If you're using this API to manage another
   * organization, use `customers/{customer}`, where customer is the customer to
   * whom the device belongs.
   * @return GoogleAppsCloudidentityDevicesV1ClientState
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], GoogleAppsCloudidentityDevicesV1ClientState::class);
  }
  /**
   * Lists the client states for the given search query.
   * (clientStates.listDevicesDeviceUsersClientStates)
   *
   * @param string $parent Required. To list all ClientStates, set this to
   * "devices/-/deviceUsers/-". To list all ClientStates owned by a DeviceUser,
   * set this to the resource name of the DeviceUser. Format:
   * devices/{device}/deviceUsers/{deviceUser}
   * @param array $optParams Optional parameters.
   *
   * @opt_param string customer Optional. [Resource
   * name](https://cloud.google.com/apis/design/resource_names) of the customer.
   * If you're using this API for your own organization, use
   * `customers/my_customer` If you're using this API to manage another
   * organization, use `customers/{customer}`, where customer is the customer to
   * whom the device belongs.
   * @opt_param string filter Optional. Additional restrictions when fetching list
   * of client states.
   * @opt_param string orderBy Optional. Order specification for client states in
   * the response.
   * @opt_param string pageToken Optional. A page token, received from a previous
   * `ListClientStates` call. Provide this to retrieve the subsequent page. When
   * paginating, all other parameters provided to `ListClientStates` must match
   * the call that provided the page token.
   * @return GoogleAppsCloudidentityDevicesV1ListClientStatesResponse
   * @throws \Google\Service\Exception
   */
  public function listDevicesDeviceUsersClientStates($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], GoogleAppsCloudidentityDevicesV1ListClientStatesResponse::class);
  }
  /**
   * Updates the client state for the device user **Note**: This method is
   * available only to customers who have one of the following SKUs: Enterprise
   * Standard, Enterprise Plus, Enterprise for Education, and Cloud Identity
   * Premium (clientStates.patch)
   *
   * @param string $name Output only. [Resource
   * name](https://cloud.google.com/apis/design/resource_names) of the ClientState
   * in format:
   * `devices/{device}/deviceUsers/{device_user}/clientState/{partner}`, where
   * partner corresponds to the partner storing the data. For partners belonging
   * to the "BeyondCorp Alliance", this is the partner ID specified to you by
   * Google. For all other callers, this is a string of the form:
   * `{customer}-suffix`, where `customer` is your customer ID. The *suffix* is
   * any string the caller specifies. This string will be displayed verbatim in
   * the administration console. This suffix is used in setting up Custom Access
   * Levels in Context-Aware Access. Your organization's customer ID can be
   * obtained from the URL: `GET
   * https://www.googleapis.com/admin/directory/v1/customers/my_customer` The `id`
   * field in the response contains the customer ID starting with the letter 'C'.
   * The customer ID to be used in this API is the string after the letter 'C'
   * (not including 'C')
   * @param GoogleAppsCloudidentityDevicesV1ClientState $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string customer Optional. [Resource
   * name](https://cloud.google.com/apis/design/resource_names) of the customer.
   * If you're using this API for your own organization, use
   * `customers/my_customer` If you're using this API to manage another
   * organization, use `customers/{customer}`, where customer is the customer to
   * whom the device belongs.
   * @opt_param string updateMask Optional. Comma-separated list of fully
   * qualified names of fields to be updated. If not specified, all updatable
   * fields in ClientState are updated.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function patch($name, GoogleAppsCloudidentityDevicesV1ClientState $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], Operation::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DevicesDeviceUsersClientStates::class, 'Google_Service_CloudIdentity_Resource_DevicesDeviceUsersClientStates');
