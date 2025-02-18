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

namespace Google\Service\Appengine\Resource;

use Google\Service\Appengine\ListServicesResponse;
use Google\Service\Appengine\Service;

/**
 * The "services" collection of methods.
 * Typical usage is:
 *  <code>
 *   $appengineService = new Google\Service\Appengine(...);
 *   $services = $appengineService->projects_locations_applications_services;
 *  </code>
 */
class ProjectsLocationsApplicationsServices extends \Google\Service\Resource
{
  /**
   * Gets the current configuration of the specified service. (services.get)
   *
   * @param string $projectsId Part of `name`. Name of the resource requested.
   * Example: apps/myapp/services/default.
   * @param string $locationsId Part of `name`. See documentation of `projectsId`.
   * @param string $applicationsId Part of `name`. See documentation of
   * `projectsId`.
   * @param string $servicesId Part of `name`. See documentation of `projectsId`.
   * @param array $optParams Optional parameters.
   * @return Service
   */
  public function get($projectsId, $locationsId, $applicationsId, $servicesId, $optParams = [])
  {
    $params = ['projectsId' => $projectsId, 'locationsId' => $locationsId, 'applicationsId' => $applicationsId, 'servicesId' => $servicesId];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Service::class);
  }
  /**
   * Lists all the services in the application.
   * (services.listProjectsLocationsApplicationsServices)
   *
   * @param string $projectsId Part of `parent`. Name of the parent Application
   * resource. Example: apps/myapp.
   * @param string $locationsId Part of `parent`. See documentation of
   * `projectsId`.
   * @param string $applicationsId Part of `parent`. See documentation of
   * `projectsId`.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int pageSize Maximum results to return per page.
   * @opt_param string pageToken Continuation token for fetching the next page of
   * results.
   * @return ListServicesResponse
   */
  public function listProjectsLocationsApplicationsServices($projectsId, $locationsId, $applicationsId, $optParams = [])
  {
    $params = ['projectsId' => $projectsId, 'locationsId' => $locationsId, 'applicationsId' => $applicationsId];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListServicesResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsApplicationsServices::class, 'Google_Service_Appengine_Resource_ProjectsLocationsApplicationsServices');
