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

namespace Google\Service\CloudComposer\Resource;

use Google\Service\CloudComposer\ListImageVersionsResponse;

/**
 * The "imageVersions" collection of methods.
 * Typical usage is:
 *  <code>
 *   $composerService = new Google\Service\CloudComposer(...);
 *   $imageVersions = $composerService->projects_locations_imageVersions;
 *  </code>
 */
class ProjectsLocationsImageVersions extends \Google\Service\Resource
{
  /**
   * List ImageVersions for provided location.
   * (imageVersions.listProjectsLocationsImageVersions)
   *
   * @param string $parent List ImageVersions in the given project and location,
   * in the form: "projects/{projectId}/locations/{locationId}"
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool includePastReleases Whether or not image versions from old
   * releases should be included.
   * @opt_param int pageSize The maximum number of image_versions to return.
   * @opt_param string pageToken The next_page_token value returned from a
   * previous List request, if any.
   * @return ListImageVersionsResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsLocationsImageVersions($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListImageVersionsResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsImageVersions::class, 'Google_Service_CloudComposer_Resource_ProjectsLocationsImageVersions');
