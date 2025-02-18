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

use Google\Service\Monitoring\ListServicesResponse;
use Google\Service\Monitoring\MonitoringEmpty;
use Google\Service\Monitoring\Service;

/**
 * The "services" collection of methods.
 * Typical usage is:
 *  <code>
 *   $monitoringService = new Google\Service\Monitoring(...);
 *   $services = $monitoringService->services;
 *  </code>
 */
class Services extends \Google\Service\Resource
{
  /**
   * Create a Service. (services.create)
   *
   * @param string $parent Required. Resource name
   * (https://cloud.google.com/monitoring/api/v3#project_name) of the parent
   * Metrics Scope. The format is: projects/[PROJECT_ID_OR_NUMBER]
   * @param Service $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string serviceId Optional. The Service id to use for this Service.
   * If omitted, an id will be generated instead. Must match the pattern
   * [a-z0-9\-]+
   * @return Service
   * @throws \Google\Service\Exception
   */
  public function create($parent, Service $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], Service::class);
  }
  /**
   * Soft delete this Service. (services.delete)
   *
   * @param string $name Required. Resource name of the Service to delete. The
   * format is: projects/[PROJECT_ID_OR_NUMBER]/services/[SERVICE_ID]
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
   * Get the named Service. (services.get)
   *
   * @param string $name Required. Resource name of the Service. The format is:
   * projects/[PROJECT_ID_OR_NUMBER]/services/[SERVICE_ID]
   * @param array $optParams Optional parameters.
   * @return Service
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Service::class);
  }
  /**
   * List Services for this Metrics Scope. (services.listServices)
   *
   * @param string $parent Required. Resource name of the parent containing the
   * listed services, either a project
   * (https://cloud.google.com/monitoring/api/v3#project_name) or a Monitoring
   * Metrics Scope. The formats are: projects/[PROJECT_ID_OR_NUMBER]
   * workspaces/[HOST_PROJECT_ID_OR_NUMBER]
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter A filter specifying what Services to return. The
   * filter supports filtering on a particular service-identifier type or one of
   * its attributes.To filter on a particular service-identifier type, the
   * identifier_case refers to which option in the identifier field is populated.
   * For example, the filter identifier_case = "CUSTOM" would match all services
   * with a value for the custom field. Valid options include "CUSTOM",
   * "APP_ENGINE", "MESH_ISTIO", and the other options listed at
   * https://cloud.google.com/monitoring/api/ref_v3/rest/v3/services#ServiceTo
   * filter on an attribute of a service-identifier type, apply the filter name by
   * using the snake case of the service-identifier type and the attribute of that
   * service-identifier type, and join the two with a period. For example, to
   * filter by the meshUid field of the MeshIstio service-identifier type, you
   * must filter on mesh_istio.mesh_uid = "123" to match all services with mesh
   * UID "123". Service-identifier types and their attributes are described at
   * https://cloud.google.com/monitoring/api/ref_v3/rest/v3/services#Service
   * @opt_param int pageSize A non-negative number that is the maximum number of
   * results to return. When 0, use default page size.
   * @opt_param string pageToken If this field is not empty then it must contain
   * the nextPageToken value returned by a previous call to this method. Using
   * this field causes the method to return additional results from the previous
   * method call.
   * @return ListServicesResponse
   * @throws \Google\Service\Exception
   */
  public function listServices($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListServicesResponse::class);
  }
  /**
   * Update this Service. (services.patch)
   *
   * @param string $name Identifier. Resource name for this Service. The format
   * is: projects/[PROJECT_ID_OR_NUMBER]/services/[SERVICE_ID]
   * @param Service $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string updateMask A set of field paths defining which fields to
   * use for the update.
   * @return Service
   * @throws \Google\Service\Exception
   */
  public function patch($name, Service $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], Service::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Services::class, 'Google_Service_Monitoring_Resource_Services');
