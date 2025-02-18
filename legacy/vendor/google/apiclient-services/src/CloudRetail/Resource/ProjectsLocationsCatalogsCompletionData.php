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

namespace Google\Service\CloudRetail\Resource;

use Google\Service\CloudRetail\GoogleCloudRetailV2ImportCompletionDataRequest;
use Google\Service\CloudRetail\GoogleLongrunningOperation;

/**
 * The "completionData" collection of methods.
 * Typical usage is:
 *  <code>
 *   $retailService = new Google\Service\CloudRetail(...);
 *   $completionData = $retailService->projects_locations_catalogs_completionData;
 *  </code>
 */
class ProjectsLocationsCatalogsCompletionData extends \Google\Service\Resource
{
  /**
   * Bulk import of processed completion dataset. Request processing is
   * asynchronous. Partial updating is not supported. The operation is
   * successfully finished only after the imported suggestions are indexed
   * successfully and ready for serving. The process takes hours. This feature is
   * only available for users who have Retail Search enabled. Enable Retail Search
   * on Cloud Console before using this feature. (completionData.import)
   *
   * @param string $parent Required. The catalog which the suggestions dataset
   * belongs to. Format:
   * `projects/1234/locations/global/catalogs/default_catalog`.
   * @param GoogleCloudRetailV2ImportCompletionDataRequest $postBody
   * @param array $optParams Optional parameters.
   * @return GoogleLongrunningOperation
   * @throws \Google\Service\Exception
   */
  public function import($parent, GoogleCloudRetailV2ImportCompletionDataRequest $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('import', [$params], GoogleLongrunningOperation::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsCatalogsCompletionData::class, 'Google_Service_CloudRetail_Resource_ProjectsLocationsCatalogsCompletionData');
