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

namespace Google\Service\OnDemandScanning\Resource;

use Google\Service\OnDemandScanning\AnalyzePackagesRequestV1;
use Google\Service\OnDemandScanning\Operation;

/**
 * The "scans" collection of methods.
 * Typical usage is:
 *  <code>
 *   $ondemandscanningService = new Google\Service\OnDemandScanning(...);
 *   $scans = $ondemandscanningService->projects_locations_scans;
 *  </code>
 */
class ProjectsLocationsScans extends \Google\Service\Resource
{
  /**
   * Initiates an analysis of the provided packages. (scans.analyzePackages)
   *
   * @param string $parent Required. The parent of the resource for which analysis
   * is requested. Format: projects/[project_name]/locations/[location]
   * @param AnalyzePackagesRequestV1 $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function analyzePackages($parent, AnalyzePackagesRequestV1 $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('analyzePackages', [$params], Operation::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsScans::class, 'Google_Service_OnDemandScanning_Resource_ProjectsLocationsScans');
