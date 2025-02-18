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

namespace Google\Service\Vision\Resource;

use Google\Service\Vision\AsyncBatchAnnotateFilesRequest;
use Google\Service\Vision\BatchAnnotateFilesRequest;
use Google\Service\Vision\BatchAnnotateFilesResponse;
use Google\Service\Vision\Operation;

/**
 * The "files" collection of methods.
 * Typical usage is:
 *  <code>
 *   $visionService = new Google\Service\Vision(...);
 *   $files = $visionService->projects_locations_files;
 *  </code>
 */
class ProjectsLocationsFiles extends \Google\Service\Resource
{
  /**
   * Service that performs image detection and annotation for a batch of files.
   * Now only "application/pdf", "image/tiff" and "image/gif" are supported. This
   * service will extract at most 5 (customers can specify which 5 in
   * AnnotateFileRequest.pages) frames (gif) or pages (pdf or tiff) from each file
   * provided and perform detection and annotation for each image extracted.
   * (files.annotate)
   *
   * @param string $parent Optional. Target project and location to make a call.
   * Format: `projects/{project-id}/locations/{location-id}`. If no parent is
   * specified, a region will be chosen automatically. Supported location-ids:
   * `us`: USA country only, `asia`: East asia areas, like Japan, Taiwan, `eu`:
   * The European Union. Example: `projects/project-A/locations/eu`.
   * @param BatchAnnotateFilesRequest $postBody
   * @param array $optParams Optional parameters.
   * @return BatchAnnotateFilesResponse
   * @throws \Google\Service\Exception
   */
  public function annotate($parent, BatchAnnotateFilesRequest $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('annotate', [$params], BatchAnnotateFilesResponse::class);
  }
  /**
   * Run asynchronous image detection and annotation for a list of generic files,
   * such as PDF files, which may contain multiple pages and multiple images per
   * page. Progress and results can be retrieved through the
   * `google.longrunning.Operations` interface. `Operation.metadata` contains
   * `OperationMetadata` (metadata). `Operation.response` contains
   * `AsyncBatchAnnotateFilesResponse` (results). (files.asyncBatchAnnotate)
   *
   * @param string $parent Optional. Target project and location to make a call.
   * Format: `projects/{project-id}/locations/{location-id}`. If no parent is
   * specified, a region will be chosen automatically. Supported location-ids:
   * `us`: USA country only, `asia`: East asia areas, like Japan, Taiwan, `eu`:
   * The European Union. Example: `projects/project-A/locations/eu`.
   * @param AsyncBatchAnnotateFilesRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function asyncBatchAnnotate($parent, AsyncBatchAnnotateFilesRequest $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('asyncBatchAnnotate', [$params], Operation::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsFiles::class, 'Google_Service_Vision_Resource_ProjectsLocationsFiles');
