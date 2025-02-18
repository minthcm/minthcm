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

namespace Google\Service\OSConfig\Resource;

use Google\Service\OSConfig\ListOSPolicyAssignmentReportsResponse;
use Google\Service\OSConfig\OSPolicyAssignmentReport;

/**
 * The "reports" collection of methods.
 * Typical usage is:
 *  <code>
 *   $osconfigService = new Google\Service\OSConfig(...);
 *   $reports = $osconfigService->projects_locations_instances_osPolicyAssignments_reports;
 *  </code>
 */
class ProjectsLocationsInstancesOsPolicyAssignmentsReports extends \Google\Service\Resource
{
  /**
   * Get the OS policy assignment report for the specified Compute Engine VM
   * instance. (reports.get)
   *
   * @param string $name Required. API resource name for OS policy assignment
   * report. Format: `/projects/{project}/locations/{location}/instances/{instance
   * }/osPolicyAssignments/{assignment}/report` For `{project}`, either `project-
   * number` or `project-id` can be provided. For `{instance_id}`, either Compute
   * Engine `instance-id` or `instance-name` can be provided. For
   * `{assignment_id}`, the OSPolicyAssignment id must be provided.
   * @param array $optParams Optional parameters.
   * @return OSPolicyAssignmentReport
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], OSPolicyAssignmentReport::class);
  }
  /**
   * List OS policy assignment reports for all Compute Engine VM instances in the
   * specified zone.
   * (reports.listProjectsLocationsInstancesOsPolicyAssignmentsReports)
   *
   * @param string $parent Required. The parent resource name. Format: `projects/{
   * project}/locations/{location}/instances/{instance}/osPolicyAssignments/{assig
   * nment}/reports` For `{project}`, either `project-number` or `project-id` can
   * be provided. For `{instance}`, either `instance-name`, `instance-id`, or `-`
   * can be provided. If '-' is provided, the response will include
   * OSPolicyAssignmentReports for all instances in the project/location. For
   * `{assignment}`, either `assignment-id` or `-` can be provided. If '-' is
   * provided, the response will include OSPolicyAssignmentReports for all
   * OSPolicyAssignments in the project/location. Either {instance} or
   * {assignment} must be `-`. For example: `projects/{project}/locations/{locatio
   * n}/instances/{instance}/osPolicyAssignments/-/reports` returns all reports
   * for the instance `projects/{project}/locations/{location}/instances/-
   * /osPolicyAssignments/{assignment-id}/reports` returns all the reports for the
   * given assignment across all instances. `projects/{project}/locations/{locatio
   * n}/instances/-/osPolicyAssignments/-/reports` returns all the reports for all
   * assignments across all instances.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter If provided, this field specifies the criteria that
   * must be met by the `OSPolicyAssignmentReport` API resource that is included
   * in the response.
   * @opt_param int pageSize The maximum number of results to return.
   * @opt_param string pageToken A pagination token returned from a previous call
   * to the `ListOSPolicyAssignmentReports` method that indicates where this
   * listing should continue from.
   * @return ListOSPolicyAssignmentReportsResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsLocationsInstancesOsPolicyAssignmentsReports($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListOSPolicyAssignmentReportsResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsInstancesOsPolicyAssignmentsReports::class, 'Google_Service_OSConfig_Resource_ProjectsLocationsInstancesOsPolicyAssignmentsReports');
