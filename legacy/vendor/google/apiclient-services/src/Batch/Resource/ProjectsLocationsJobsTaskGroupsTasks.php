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

namespace Google\Service\Batch\Resource;

use Google\Service\Batch\ListTasksResponse;
use Google\Service\Batch\Task;

/**
 * The "tasks" collection of methods.
 * Typical usage is:
 *  <code>
 *   $batchService = new Google\Service\Batch(...);
 *   $tasks = $batchService->projects_locations_jobs_taskGroups_tasks;
 *  </code>
 */
class ProjectsLocationsJobsTaskGroupsTasks extends \Google\Service\Resource
{
  /**
   * Return a single Task. (tasks.get)
   *
   * @param string $name Required. Task name.
   * @param array $optParams Optional parameters.
   * @return Task
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Task::class);
  }
  /**
   * List Tasks associated with a job.
   * (tasks.listProjectsLocationsJobsTaskGroupsTasks)
   *
   * @param string $parent Required. Name of a TaskGroup from which Tasks are
   * being requested. Pattern:
   * "projects/{project}/locations/{location}/jobs/{job}/taskGroups/{task_group}"
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter Task filter, null filter matches all Tasks. Filter
   * string should be of the format State=TaskStatus.State e.g. State=RUNNING
   * @opt_param int pageSize Page size.
   * @opt_param string pageToken Page token.
   * @return ListTasksResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsLocationsJobsTaskGroupsTasks($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListTasksResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsJobsTaskGroupsTasks::class, 'Google_Service_Batch_Resource_ProjectsLocationsJobsTaskGroupsTasks');
