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

use Google\Service\Dataflow\GetDebugConfigRequest;
use Google\Service\Dataflow\GetDebugConfigResponse;
use Google\Service\Dataflow\SendDebugCaptureRequest;
use Google\Service\Dataflow\SendDebugCaptureResponse;

/**
 * The "debug" collection of methods.
 * Typical usage is:
 *  <code>
 *   $dataflowService = new Google\Service\Dataflow(...);
 *   $debug = $dataflowService->projects_jobs_debug;
 *  </code>
 */
class ProjectsJobsDebug extends \Google\Service\Resource
{
  /**
   * Get encoded debug configuration for component. Not cacheable.
   * (debug.getConfig)
   *
   * @param string $projectId The project id.
   * @param string $jobId The job id.
   * @param GetDebugConfigRequest $postBody
   * @param array $optParams Optional parameters.
   * @return GetDebugConfigResponse
   * @throws \Google\Service\Exception
   */
  public function getConfig($projectId, $jobId, GetDebugConfigRequest $postBody, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'jobId' => $jobId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('getConfig', [$params], GetDebugConfigResponse::class);
  }
  /**
   * Send encoded debug capture data for component. (debug.sendCapture)
   *
   * @param string $projectId The project id.
   * @param string $jobId The job id.
   * @param SendDebugCaptureRequest $postBody
   * @param array $optParams Optional parameters.
   * @return SendDebugCaptureResponse
   * @throws \Google\Service\Exception
   */
  public function sendCapture($projectId, $jobId, SendDebugCaptureRequest $postBody, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'jobId' => $jobId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('sendCapture', [$params], SendDebugCaptureResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsJobsDebug::class, 'Google_Service_Dataflow_Resource_ProjectsJobsDebug');
