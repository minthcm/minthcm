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

namespace Google\Service\Aiplatform\Resource;

use Google\Service\Aiplatform\GoogleCloudAiplatformV1ListDataItemsResponse;

/**
 * The "dataItems" collection of methods.
 * Typical usage is:
 *  <code>
 *   $aiplatformService = new Google\Service\Aiplatform(...);
 *   $dataItems = $aiplatformService->projects_locations_datasets_dataItems;
 *  </code>
 */
class ProjectsLocationsDatasetsDataItems extends \Google\Service\Resource
{
  /**
   * Lists DataItems in a Dataset.
   * (dataItems.listProjectsLocationsDatasetsDataItems)
   *
   * @param string $parent Required. The resource name of the Dataset to list
   * DataItems from. Format:
   * `projects/{project}/locations/{location}/datasets/{dataset}`
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter The standard list filter.
   * @opt_param string orderBy A comma-separated list of fields to order by,
   * sorted in ascending order. Use "desc" after a field name for descending.
   * @opt_param int pageSize The standard list page size.
   * @opt_param string pageToken The standard list page token.
   * @opt_param string readMask Mask specifying which fields to read.
   * @return GoogleCloudAiplatformV1ListDataItemsResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsLocationsDatasetsDataItems($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], GoogleCloudAiplatformV1ListDataItemsResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsDatasetsDataItems::class, 'Google_Service_Aiplatform_Resource_ProjectsLocationsDatasetsDataItems');
