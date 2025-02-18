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

namespace Google\Service\Dataflow\Resource;

use Google\Service\Dataflow\ListSnapshotsResponse;

/**
 * The "snapshots" collection of methods.
 * Typical usage is:
 *  <code>
 *   $dataflowService = new Google\Service\Dataflow(...);
 *   $snapshots = $dataflowService->projects_locations_jobs_snapshots;
 *  </code>
 */
class ProjectsLocationsJobsSnapshots extends \Google\Service\Resource
{
  /**
   * Lists snapshots. (snapshots.listProjectsLocationsJobsSnapshots)
   *
   * @param string $projectId The project ID to list snapshots for.
   * @param string $location The location to list snapshots in.
   * @param string $jobId If specified, list snapshots created from this job.
   * @param array $optParams Optional parameters.
   * @return ListSnapshotsResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsLocationsJobsSnapshots($projectId, $location, $jobId, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'location' => $location, 'jobId' => $jobId];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListSnapshotsResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsJobsSnapshots::class, 'Google_Service_Dataflow_Resource_ProjectsLocationsJobsSnapshots');
