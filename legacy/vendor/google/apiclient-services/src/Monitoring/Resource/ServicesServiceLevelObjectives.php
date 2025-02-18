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

use Google\Service\Monitoring\ListServiceLevelObjectivesResponse;
use Google\Service\Monitoring\MonitoringEmpty;
use Google\Service\Monitoring\ServiceLevelObjective;

/**
 * The "serviceLevelObjectives" collection of methods.
 * Typical usage is:
 *  <code>
 *   $monitoringService = new Google\Service\Monitoring(...);
 *   $serviceLevelObjectives = $monitoringService->services_serviceLevelObjectives;
 *  </code>
 */
class ServicesServiceLevelObjectives extends \Google\Service\Resource
{
  /**
   * Create a ServiceLevelObjective for the given Service.
   * (serviceLevelObjectives.create)
   *
   * @param string $parent Required. Resource name of the parent Service. The
   * format is: projects/[PROJECT_ID_OR_NUMBER]/services/[SERVICE_ID]
   * @param ServiceLevelObjective $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string serviceLevelObjectiveId Optional. The ServiceLevelObjective
   * id to use for this ServiceLevelObjective. If omitted, an id will be generated
   * instead. Must match the pattern ^[a-zA-Z0-9-_:.]+$
   * @return ServiceLevelObjective
   * @throws \Google\Service\Exception
   */
  public function create($parent, ServiceLevelObjective $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], ServiceLevelObjective::class);
  }
  /**
   * Delete the given ServiceLevelObjective. (serviceLevelObjectives.delete)
   *
   * @param string $name Required. Resource name of the ServiceLevelObjective to
   * delete. The format is: projects/[PROJECT_ID_OR_NUMBER]/services/[SERVICE_ID]/
   * serviceLevelObjectives/[SLO_NAME]
   * @param array $optParams Optional parameters.
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
   * Get a ServiceLevelObjective by name. (serviceLevelObjectives.get)
   *
   * @param string $name Required. Resource name of the ServiceLevelObjective to
   * get. The format is: projects/[PROJECT_ID_OR_NUMBER]/services/[SERVICE_ID]/ser
   * viceLevelObjectives/[SLO_NAME]
   * @param array $optParams Optional parameters.
   *
   * @opt_param string view View of the ServiceLevelObjective to return. If
   * DEFAULT, return the ServiceLevelObjective as originally defined. If EXPLICIT
   * and the ServiceLevelObjective is defined in terms of a BasicSli, replace the
   * BasicSli with a RequestBasedSli spelling out how the SLI is computed.
   * @return ServiceLevelObjective
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], ServiceLevelObjective::class);
  }
  /**
   * List the ServiceLevelObjectives for the given Service.
   * (serviceLevelObjectives.listServicesServiceLevelObjectives)
   *
   * @param string $parent Required. Resource name of the parent containing the
   * listed SLOs, either a project or a Monitoring Metrics Scope. The formats are:
   * projects/[PROJECT_ID_OR_NUMBER]/services/[SERVICE_ID]
   * workspaces/[HOST_PROJECT_ID_OR_NUMBER]/services/-
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter A filter specifying what ServiceLevelObjectives to
   * return.
   * @opt_param int pageSize A non-negative number that is the maximum number of
   * results to return. When 0, use default page size.
   * @opt_param string pageToken If this field is not empty then it must contain
   * the nextPageToken value returned by a previous call to this method. Using
   * this field causes the method to return additional results from the previous
   * method call.
   * @opt_param string view View of the ServiceLevelObjectives to return. If
   * DEFAULT, return each ServiceLevelObjective as originally defined. If EXPLICIT
   * and the ServiceLevelObjective is defined in terms of a BasicSli, replace the
   * BasicSli with a RequestBasedSli spelling out how the SLI is computed.
   * @return ListServiceLevelObjectivesResponse
   * @throws \Google\Service\Exception
   */
  public function listServicesServiceLevelObjectives($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListServiceLevelObjectivesResponse::class);
  }
  /**
   * Update the given ServiceLevelObjective. (serviceLevelObjectives.patch)
   *
   * @param string $name Identifier. Resource name for this ServiceLevelObjective.
   * The format is: projects/[PROJECT_ID_OR_NUMBER]/services/[SERVICE_ID]/serviceL
   * evelObjectives/[SLO_NAME]
   * @param ServiceLevelObjective $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string updateMask A set of field paths defining which fields to
   * use for the update.
   * @return ServiceLevelObjective
   * @throws \Google\Service\Exception
   */
  public function patch($name, ServiceLevelObjective $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], ServiceLevelObjective::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ServicesServiceLevelObjectives::class, 'Google_Service_Monitoring_Resource_ServicesServiceLevelObjectives');
