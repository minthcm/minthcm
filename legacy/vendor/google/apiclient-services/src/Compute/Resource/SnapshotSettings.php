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

namespace Google\Service\Compute\Resource;

use Google\Service\Compute\Operation;
use Google\Service\Compute\SnapshotSettings as SnapshotSettingsModel;

/**
 * The "snapshotSettings" collection of methods.
 * Typical usage is:
 *  <code>
 *   $computeService = new Google\Service\Compute(...);
 *   $snapshotSettings = $computeService->snapshotSettings;
 *  </code>
 */
class SnapshotSettings extends \Google\Service\Resource
{
  /**
   * Get snapshot settings. (snapshotSettings.get)
   *
   * @param string $project Project ID for this request.
   * @param array $optParams Optional parameters.
   * @return SnapshotSettingsModel
   * @throws \Google\Service\Exception
   */
  public function get($project, $optParams = [])
  {
    $params = ['project' => $project];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], SnapshotSettingsModel::class);
  }
  /**
   * Patch snapshot settings. (snapshotSettings.patch)
   *
   * @param string $project Project ID for this request.
   * @param SnapshotSettingsModel $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string requestId An optional request ID to identify requests.
   * Specify a unique request ID so that if you must retry your request, the
   * server will know to ignore the request if it has already been completed. For
   * example, consider a situation where you make an initial request and the
   * request times out. If you make the request again with the same request ID,
   * the server can check if original operation with the same request ID was
   * received, and if so, will ignore the second request. This prevents clients
   * from accidentally creating duplicate commitments. The request ID must be a
   * valid UUID with the exception that zero UUID is not supported (
   * 00000000-0000-0000-0000-000000000000).
   * @opt_param string updateMask update_mask indicates fields to be updated as
   * part of this request.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function patch($project, SnapshotSettingsModel $postBody, $optParams = [])
  {
    $params = ['project' => $project, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], Operation::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SnapshotSettings::class, 'Google_Service_Compute_Resource_SnapshotSettings');
