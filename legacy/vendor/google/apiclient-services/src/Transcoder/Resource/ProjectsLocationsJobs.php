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

namespace Google\Service\Transcoder\Resource;

use Google\Service\Transcoder\Job;
use Google\Service\Transcoder\ListJobsResponse;
use Google\Service\Transcoder\TranscoderEmpty;

/**
 * The "jobs" collection of methods.
 * Typical usage is:
 *  <code>
 *   $transcoderService = new Google\Service\Transcoder(...);
 *   $jobs = $transcoderService->projects_locations_jobs;
 *  </code>
 */
class ProjectsLocationsJobs extends \Google\Service\Resource
{
  /**
   * Creates a job in the specified region. (jobs.create)
   *
   * @param string $parent Required. The parent location to create and process
   * this job. Format: `projects/{project}/locations/{location}`
   * @param Job $postBody
   * @param array $optParams Optional parameters.
   * @return Job
   * @throws \Google\Service\Exception
   */
  public function create($parent, Job $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], Job::class);
  }
  /**
   * Deletes a job. (jobs.delete)
   *
   * @param string $name Required. The name of the job to delete. Format:
   * `projects/{project}/locations/{location}/jobs/{job}`
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool allowMissing If set to true, and the job is not found, the
   * request will succeed but no action will be taken on the server.
   * @return TranscoderEmpty
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], TranscoderEmpty::class);
  }
  /**
   * Returns the job data. (jobs.get)
   *
   * @param string $name Required. The name of the job to retrieve. Format:
   * `projects/{project}/locations/{location}/jobs/{job}`
   * @param array $optParams Optional parameters.
   * @return Job
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Job::class);
  }
  /**
   * Lists jobs in the specified region. (jobs.listProjectsLocationsJobs)
   *
   * @param string $parent Required. Format:
   * `projects/{project}/locations/{location}`
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter The filter expression, following the syntax outlined
   * in https://google.aip.dev/160.
   * @opt_param string orderBy One or more fields to compare and use to sort the
   * output. See https://google.aip.dev/132#ordering.
   * @opt_param int pageSize The maximum number of items to return.
   * @opt_param string pageToken The `next_page_token` value returned from a
   * previous List request, if any.
   * @return ListJobsResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsLocationsJobs($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListJobsResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsJobs::class, 'Google_Service_Transcoder_Resource_ProjectsLocationsJobs');
