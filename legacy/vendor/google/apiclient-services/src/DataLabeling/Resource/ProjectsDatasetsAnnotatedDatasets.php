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

namespace Google\Service\DataLabeling\Resource;

use Google\Service\DataLabeling\GoogleCloudDatalabelingV1beta1AnnotatedDataset;
use Google\Service\DataLabeling\GoogleCloudDatalabelingV1beta1ListAnnotatedDatasetsResponse;
use Google\Service\DataLabeling\GoogleProtobufEmpty;

/**
 * The "annotatedDatasets" collection of methods.
 * Typical usage is:
 *  <code>
 *   $datalabelingService = new Google\Service\DataLabeling(...);
 *   $annotatedDatasets = $datalabelingService->projects_datasets_annotatedDatasets;
 *  </code>
 */
class ProjectsDatasetsAnnotatedDatasets extends \Google\Service\Resource
{
  /**
   * Deletes an annotated dataset by resource name. (annotatedDatasets.delete)
   *
   * @param string $name Required. Name of the annotated dataset to delete,
   * format: projects/{project_id}/datasets/{dataset_id}/annotatedDatasets/
   * {annotated_dataset_id}
   * @param array $optParams Optional parameters.
   * @return GoogleProtobufEmpty
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], GoogleProtobufEmpty::class);
  }
  /**
   * Gets an annotated dataset by resource name. (annotatedDatasets.get)
   *
   * @param string $name Required. Name of the annotated dataset to get, format:
   * projects/{project_id}/datasets/{dataset_id}/annotatedDatasets/
   * {annotated_dataset_id}
   * @param array $optParams Optional parameters.
   * @return GoogleCloudDatalabelingV1beta1AnnotatedDataset
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], GoogleCloudDatalabelingV1beta1AnnotatedDataset::class);
  }
  /**
   * Lists annotated datasets for a dataset. Pagination is supported.
   * (annotatedDatasets.listProjectsDatasetsAnnotatedDatasets)
   *
   * @param string $parent Required. Name of the dataset to list annotated
   * datasets, format: projects/{project_id}/datasets/{dataset_id}
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter Optional. Filter is not supported at this moment.
   * @opt_param int pageSize Optional. Requested page size. Server may return
   * fewer results than requested. Default value is 100.
   * @opt_param string pageToken Optional. A token identifying a page of results
   * for the server to return. Typically obtained by
   * ListAnnotatedDatasetsResponse.next_page_token of the previous
   * [DataLabelingService.ListAnnotatedDatasets] call. Return first page if empty.
   * @return GoogleCloudDatalabelingV1beta1ListAnnotatedDatasetsResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsDatasetsAnnotatedDatasets($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], GoogleCloudDatalabelingV1beta1ListAnnotatedDatasetsResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsDatasetsAnnotatedDatasets::class, 'Google_Service_DataLabeling_Resource_ProjectsDatasetsAnnotatedDatasets');
