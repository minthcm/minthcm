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

namespace Google\Service\MigrationCenterAPI\Resource;

use Google\Service\MigrationCenterAPI\ImportDataFile;
use Google\Service\MigrationCenterAPI\ListImportDataFilesResponse;
use Google\Service\MigrationCenterAPI\Operation;

/**
 * The "importDataFiles" collection of methods.
 * Typical usage is:
 *  <code>
 *   $migrationcenterService = new Google\Service\MigrationCenterAPI(...);
 *   $importDataFiles = $migrationcenterService->projects_locations_importJobs_importDataFiles;
 *  </code>
 */
class ProjectsLocationsImportJobsImportDataFiles extends \Google\Service\Resource
{
  /**
   * Creates an import data file. (importDataFiles.create)
   *
   * @param string $parent Required. Name of the parent of the ImportDataFile.
   * @param ImportDataFile $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string importDataFileId Required. The ID of the new data file.
   * @opt_param string requestId Optional. An optional request ID to identify
   * requests. Specify a unique request ID so that if you must retry your request,
   * the server will know to ignore the request if it has already been completed.
   * The server will guarantee that for at least 60 minutes since the first
   * request. For example, consider a situation where you make an initial request
   * and the request times out. If you make the request again with the same
   * request ID, the server can check if original operation with the same request
   * ID was received, and if so, will ignore the second request. This prevents
   * clients from accidentally creating duplicate commitments. The request ID must
   * be a valid UUID with the exception that zero UUID is not supported
   * (00000000-0000-0000-0000-000000000000).
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function create($parent, ImportDataFile $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], Operation::class);
  }
  /**
   * Delete an import data file. (importDataFiles.delete)
   *
   * @param string $name Required. Name of the ImportDataFile to delete.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string requestId Optional. An optional request ID to identify
   * requests. Specify a unique request ID so that if you must retry your request,
   * the server will know to ignore the request if it has already been completed.
   * The server will guarantee that for at least 60 minutes after the first
   * request. For example, consider a situation where you make an initial request
   * and the request times out. If you make the request again with the same
   * request ID, the server can check if original operation with the same request
   * ID was received, and if so, will ignore the second request. This prevents
   * clients from accidentally creating duplicate commitments. The request ID must
   * be a valid UUID with the exception that zero UUID is not supported
   * (00000000-0000-0000-0000-000000000000).
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], Operation::class);
  }
  /**
   * Gets an import data file. (importDataFiles.get)
   *
   * @param string $name Required. Name of the ImportDataFile.
   * @param array $optParams Optional parameters.
   * @return ImportDataFile
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], ImportDataFile::class);
  }
  /**
   * List import data files.
   * (importDataFiles.listProjectsLocationsImportJobsImportDataFiles)
   *
   * @param string $parent Required. Name of the parent of the `ImportDataFiles`
   * resource.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter Filtering results.
   * @opt_param string orderBy Field to sort by. See
   * https://google.aip.dev/132#ordering for more details.
   * @opt_param int pageSize The maximum number of data files to return. The
   * service may return fewer than this value. If unspecified, at most 500 data
   * files will be returned. The maximum value is 1000; values above 1000 will be
   * coerced to 1000.
   * @opt_param string pageToken A page token, received from a previous
   * `ListImportDataFiles` call. Provide this to retrieve the subsequent page.
   * When paginating, all other parameters provided to `ListImportDataFiles` must
   * match the call that provided the page token.
   * @return ListImportDataFilesResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsLocationsImportJobsImportDataFiles($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListImportDataFilesResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsImportJobsImportDataFiles::class, 'Google_Service_MigrationCenterAPI_Resource_ProjectsLocationsImportJobsImportDataFiles');
