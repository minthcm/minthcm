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

use Google\Service\Dataflow\LeaseWorkItemRequest;
use Google\Service\Dataflow\LeaseWorkItemResponse;
use Google\Service\Dataflow\ReportWorkItemStatusRequest;
use Google\Service\Dataflow\ReportWorkItemStatusResponse;

/**
 * The "workItems" collection of methods.
 * Typical usage is:
 *  <code>
 *   $dataflowService = new Google\Service\Dataflow(...);
 *   $workItems = $dataflowService->projects_jobs_workItems;
 *  </code>
 */
class ProjectsJobsWorkItems extends \Google\Service\Resource
{
  /**
   * Leases a dataflow WorkItem to run. (workItems.lease)
   *
   * @param string $projectId Identifies the project this worker belongs to.
   * @param string $jobId Identifies the workflow job this worker belongs to.
   * @param LeaseWorkItemRequest $postBody
   * @param array $optParams Optional parameters.
   * @return LeaseWorkItemResponse
   * @throws \Google\Service\Exception
   */
  public function lease($projectId, $jobId, LeaseWorkItemRequest $postBody, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'jobId' => $jobId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('lease', [$params], LeaseWorkItemResponse::class);
  }
  /**
   * Reports the status of dataflow WorkItems leased by a worker.
   * (workItems.reportStatus)
   *
   * @param string $projectId The project which owns the WorkItem's job.
   * @param string $jobId The job which the WorkItem is part of.
   * @param ReportWorkItemStatusRequest $postBody
   * @param array $optParams Optional parameters.
   * @return ReportWorkItemStatusResponse
   * @throws \Google\Service\Exception
   */
  public function reportStatus($projectId, $jobId, ReportWorkItemStatusRequest $postBody, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'jobId' => $jobId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('reportStatus', [$params], ReportWorkItemStatusResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsJobsWorkItems::class, 'Google_Service_Dataflow_Resource_ProjectsJobsWorkItems');
