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

use Google\Service\Monitoring\ListGroupMembersResponse;

/**
 * The "members" collection of methods.
 * Typical usage is:
 *  <code>
 *   $monitoringService = new Google\Service\Monitoring(...);
 *   $members = $monitoringService->projects_groups_members;
 *  </code>
 */
class ProjectsGroupsMembers extends \Google\Service\Resource
{
  /**
   * Lists the monitored resources that are members of a group.
   * (members.listProjectsGroupsMembers)
   *
   * @param string $name Required. The group whose members are listed. The format
   * is: projects/[PROJECT_ID_OR_NUMBER]/groups/[GROUP_ID]
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter An optional list filter
   * (https://cloud.google.com/monitoring/api/learn_more#filtering) describing the
   * members to be returned. The filter may reference the type, labels, and
   * metadata of monitored resources that comprise the group. For example, to
   * return only resources representing Compute Engine VM instances, use this
   * filter: `resource.type = "gce_instance"`
   * @opt_param string interval.endTime Required. The end of the time interval.
   * @opt_param string interval.startTime Optional. The beginning of the time
   * interval. The default value for the start time is the end time. The start
   * time must not be later than the end time.
   * @opt_param int pageSize A positive number that is the maximum number of
   * results to return.
   * @opt_param string pageToken If this field is not empty then it must contain
   * the next_page_token value returned by a previous call to this method. Using
   * this field causes the method to return additional results from the previous
   * method call.
   * @return ListGroupMembersResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsGroupsMembers($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListGroupMembersResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsGroupsMembers::class, 'Google_Service_Monitoring_Resource_ProjectsGroupsMembers');
