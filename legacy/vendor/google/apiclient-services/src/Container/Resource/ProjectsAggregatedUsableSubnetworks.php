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

namespace Google\Service\Container\Resource;

use Google\Service\Container\ListUsableSubnetworksResponse;

/**
 * The "usableSubnetworks" collection of methods.
 * Typical usage is:
 *  <code>
 *   $containerService = new Google\Service\Container(...);
 *   $usableSubnetworks = $containerService->projects_aggregated_usableSubnetworks;
 *  </code>
 */
class ProjectsAggregatedUsableSubnetworks extends \Google\Service\Resource
{
  /**
   * Lists subnetworks that are usable for creating clusters in a project.
   * (usableSubnetworks.listProjectsAggregatedUsableSubnetworks)
   *
   * @param string $parent The parent project where subnetworks are usable.
   * Specified in the format `projects`.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter Filtering currently only supports equality on the
   * networkProjectId and must be in the form: "networkProjectId=[PROJECTID]",
   * where `networkProjectId` is the project which owns the listed subnetworks.
   * This defaults to the parent project ID.
   * @opt_param int pageSize The max number of results per page that should be
   * returned. If the number of available results is larger than `page_size`, a
   * `next_page_token` is returned which can be used to get the next page of
   * results in subsequent requests. Acceptable values are 0 to 500, inclusive.
   * (Default: 500)
   * @opt_param string pageToken Specifies a page token to use. Set this to the
   * nextPageToken returned by previous list requests to get the next page of
   * results.
   * @return ListUsableSubnetworksResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsAggregatedUsableSubnetworks($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListUsableSubnetworksResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsAggregatedUsableSubnetworks::class, 'Google_Service_Container_Resource_ProjectsAggregatedUsableSubnetworks');
