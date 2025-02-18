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

namespace Google\Service\DriveLabels\Resource;

use Google\Service\DriveLabels\GoogleAppsDriveLabelsV2ListLabelLocksResponse;

/**
 * The "locks" collection of methods.
 * Typical usage is:
 *  <code>
 *   $drivelabelsService = new Google\Service\DriveLabels(...);
 *   $locks = $drivelabelsService->labels_locks;
 *  </code>
 */
class LabelsLocks extends \Google\Service\Resource
{
  /**
   * Lists the LabelLocks on a Label. (locks.listLabelsLocks)
   *
   * @param string $parent Required. Label on which Locks are applied. Format:
   * labels/{label}
   * @param array $optParams Optional parameters.
   *
   * @opt_param int pageSize Maximum number of Locks to return per page. Default:
   * 100. Max: 200.
   * @opt_param string pageToken The token of the page to return.
   * @return GoogleAppsDriveLabelsV2ListLabelLocksResponse
   * @throws \Google\Service\Exception
   */
  public function listLabelsLocks($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], GoogleAppsDriveLabelsV2ListLabelLocksResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(LabelsLocks::class, 'Google_Service_DriveLabels_Resource_LabelsLocks');
