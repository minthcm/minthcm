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

namespace Google\Service\BigtableAdmin\Resource;

use Google\Service\BigtableAdmin\AppProfile;
use Google\Service\BigtableAdmin\BigtableadminEmpty;
use Google\Service\BigtableAdmin\ListAppProfilesResponse;
use Google\Service\BigtableAdmin\Operation;

/**
 * The "appProfiles" collection of methods.
 * Typical usage is:
 *  <code>
 *   $bigtableadminService = new Google\Service\BigtableAdmin(...);
 *   $appProfiles = $bigtableadminService->projects_instances_appProfiles;
 *  </code>
 */
class ProjectsInstancesAppProfiles extends \Google\Service\Resource
{
  /**
   * Creates an app profile within an instance. (appProfiles.create)
   *
   * @param string $parent Required. The unique name of the instance in which to
   * create the new app profile. Values are of the form
   * `projects/{project}/instances/{instance}`.
   * @param AppProfile $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string appProfileId Required. The ID to be used when referring to
   * the new app profile within its instance, e.g., just `myprofile` rather than
   * `projects/myproject/instances/myinstance/appProfiles/myprofile`.
   * @opt_param bool ignoreWarnings If true, ignore safety checks when creating
   * the app profile.
   * @return AppProfile
   * @throws \Google\Service\Exception
   */
  public function create($parent, AppProfile $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], AppProfile::class);
  }
  /**
   * Deletes an app profile from an instance. (appProfiles.delete)
   *
   * @param string $name Required. The unique name of the app profile to be
   * deleted. Values are of the form
   * `projects/{project}/instances/{instance}/appProfiles/{app_profile}`.
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool ignoreWarnings Required. If true, ignore safety checks when
   * deleting the app profile.
   * @return BigtableadminEmpty
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], BigtableadminEmpty::class);
  }
  /**
   * Gets information about an app profile. (appProfiles.get)
   *
   * @param string $name Required. The unique name of the requested app profile.
   * Values are of the form
   * `projects/{project}/instances/{instance}/appProfiles/{app_profile}`.
   * @param array $optParams Optional parameters.
   * @return AppProfile
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], AppProfile::class);
  }
  /**
   * Lists information about app profiles in an instance.
   * (appProfiles.listProjectsInstancesAppProfiles)
   *
   * @param string $parent Required. The unique name of the instance for which a
   * list of app profiles is requested. Values are of the form
   * `projects/{project}/instances/{instance}`. Use `{instance} = '-'` to list
   * AppProfiles for all Instances in a project, e.g.,
   * `projects/myproject/instances/-`.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int pageSize Maximum number of results per page. A page_size of
   * zero lets the server choose the number of items to return. A page_size which
   * is strictly positive will return at most that many items. A negative
   * page_size will cause an error. Following the first request, subsequent
   * paginated calls are not required to pass a page_size. If a page_size is set
   * in subsequent calls, it must match the page_size given in the first request.
   * @opt_param string pageToken The value of `next_page_token` returned by a
   * previous call.
   * @return ListAppProfilesResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsInstancesAppProfiles($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListAppProfilesResponse::class);
  }
  /**
   * Updates an app profile within an instance. (appProfiles.patch)
   *
   * @param string $name The unique name of the app profile. Values are of the
   * form `projects/{project}/instances/{instance}/appProfiles/_a-zA-Z0-9*`.
   * @param AppProfile $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool ignoreWarnings If true, ignore safety checks when updating
   * the app profile.
   * @opt_param string updateMask Required. The subset of app profile fields which
   * should be replaced. If unset, all fields will be replaced.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function patch($name, AppProfile $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], Operation::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsInstancesAppProfiles::class, 'Google_Service_BigtableAdmin_Resource_ProjectsInstancesAppProfiles');
