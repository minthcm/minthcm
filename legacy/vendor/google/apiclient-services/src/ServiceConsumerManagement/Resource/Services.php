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

namespace Google\Service\ServiceConsumerManagement\Resource;

use Google\Service\ServiceConsumerManagement\SearchTenancyUnitsResponse;

/**
 * The "services" collection of methods.
 * Typical usage is:
 *  <code>
 *   $serviceconsumermanagementService = new Google\Service\ServiceConsumerManagement(...);
 *   $services = $serviceconsumermanagementService->services;
 *  </code>
 */
class Services extends \Google\Service\Resource
{
  /**
   * Search tenancy units for a managed service. (services.search)
   *
   * @param string $parent Required. Service for which search is performed.
   * services/{service} {service} the name of a service, for example
   * 'service.googleapis.com'.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int pageSize Optional. The maximum number of results returned by
   * this request. Currently, the default maximum is set to 1000. If `page_size`
   * isn't provided or the size provided is a number larger than 1000, it's
   * automatically set to 1000.
   * @opt_param string pageToken Optional. The continuation token, which is used
   * to page through large result sets. To get the next page of results, set this
   * parameter to the value of `nextPageToken` from the previous response.
   * @opt_param string query Optional. Set a query `{expression}` for querying
   * tenancy units. Your `{expression}` must be in the format:
   * `field_name=literal_string`. The `field_name` is the name of the field you
   * want to compare. Supported fields are `tenant_resources.tag` and
   * `tenant_resources.resource`. For example, to search tenancy units that
   * contain at least one tenant resource with a given tag 'xyz', use the query
   * `tenant_resources.tag=xyz`. To search tenancy units that contain at least one
   * tenant resource with a given resource name 'projects/123456', use the query
   * `tenant_resources.resource=projects/123456`. Multiple expressions can be
   * joined with `AND`s. Tenancy units must match all expressions to be included
   * in the result set. For example, `tenant_resources.tag=xyz AND
   * tenant_resources.resource=projects/123456`
   * @return SearchTenancyUnitsResponse
   * @throws \Google\Service\Exception
   */
  public function search($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('search', [$params], SearchTenancyUnitsResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Services::class, 'Google_Service_ServiceConsumerManagement_Resource_Services');
