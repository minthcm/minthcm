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

namespace Google\Service\Monitoring\Resource;

use Google\Service\Monitoring\Group;
use Google\Service\Monitoring\ListGroupsResponse;
use Google\Service\Monitoring\MonitoringEmpty;

/**
 * The "groups" collection of methods.
 * Typical usage is:
 *  <code>
 *   $monitoringService = new Google\Service\Monitoring(...);
 *   $groups = $monitoringService->projects_groups;
 *  </code>
 */
class ProjectsGroups extends \Google\Service\Resource
{
  /**
   * Creates a new group. (groups.create)
   *
   * @param string $name Required. The project
   * (https://cloud.google.com/monitoring/api/v3#project_name) in which to create
   * the group. The format is: projects/[PROJECT_ID_OR_NUMBER]
   * @param Group $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool validateOnly If true, validate this request but do not create
   * the group.
   * @return Group
   * @throws \Google\Service\Exception
   */
  public function create($name, Group $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], Group::class);
  }
  /**
   * Deletes an existing group. (groups.delete)
   *
   * @param string $name Required. The group to delete. The format is:
   * projects/[PROJECT_ID_OR_NUMBER]/groups/[GROUP_ID]
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool recursive If this field is true, then the request means to
   * delete a group with all its descendants. Otherwise, the request means to
   * delete a group only when it has no descendants. The default value is false.
   * @return MonitoringEmpty
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], MonitoringEmpty::class);
  }
  /**
   * Gets a single group. (groups.get)
   *
   * @param string $name Required. The group to retrieve. The format is:
   * projects/[PROJECT_ID_OR_NUMBER]/groups/[GROUP_ID]
   * @param array $optParams Optional parameters.
   * @return Group
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Group::class);
  }
  /**
   * Lists the existing groups. (groups.listProjectsGroups)
   *
   * @param string $name Required. The project
   * (https://cloud.google.com/monitoring/api/v3#project_name) whose groups are to
   * be listed. The format is: projects/[PROJECT_ID_OR_NUMBER]
   * @param array $optParams Optional parameters.
   *
   * @opt_param string ancestorsOfGroup A group name. The format is:
   * projects/[PROJECT_ID_OR_NUMBER]/groups/[GROUP_ID] Returns groups that are
   * ancestors of the specified group. The groups are returned in order, starting
   * with the immediate parent and ending with the most distant ancestor. If the
   * specified group has no immediate parent, the results are empty.
   * @opt_param string childrenOfGroup A group name. The format is:
   * projects/[PROJECT_ID_OR_NUMBER]/groups/[GROUP_ID] Returns groups whose
   * parent_name field contains the group name. If no groups have this parent, the
   * results are empty.
   * @opt_param string descendantsOfGroup A group name. The format is:
   * projects/[PROJECT_ID_OR_NUMBER]/groups/[GROUP_ID] Returns the descendants of
   * the specified group. This is a superset of the results returned by the
   * children_of_group filter, and includes children-of-children, and so forth.
   * @opt_param int pageSize A positive number that is the maximum number of
   * results to return.
   * @opt_param string pageToken If this field is not empty then it must contain
   * the next_page_token value returned by a previous call to this method. Using
   * this field causes the method to return additional results from the previous
   * method call.
   * @return ListGroupsResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsGroups($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListGroupsResponse::class);
  }
  /**
   * Updates an existing group. You can change any group attributes except name.
   * (groups.update)
   *
   * @param string $name Output only. The name of this group. The format is:
   * projects/[PROJECT_ID_OR_NUMBER]/groups/[GROUP_ID] When creating a group, this
   * field is ignored and a new name is created consisting of the project
   * specified in the call to CreateGroup and a unique [GROUP_ID] that is
   * generated automatically.
   * @param Group $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool validateOnly If true, validate this request but do not update
   * the existing group.
   * @return Group
   * @throws \Google\Service\Exception
   */
  public function update($name, Group $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('update', [$params], Group::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsGroups::class, 'Google_Service_Monitoring_Resource_ProjectsGroups');
