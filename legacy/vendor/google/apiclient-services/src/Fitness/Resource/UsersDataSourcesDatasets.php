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

namespace Google\Service\Fitness\Resource;

use Google\Service\Fitness\Dataset;

/**
 * The "datasets" collection of methods.
 * Typical usage is:
 *  <code>
 *   $fitnessService = new Google\Service\Fitness(...);
 *   $datasets = $fitnessService->users_dataSources_datasets;
 *  </code>
 */
class UsersDataSourcesDatasets extends \Google\Service\Resource
{
  /**
   * Performs an inclusive delete of all data points whose start and end times
   * have any overlap with the time range specified by the dataset ID. For most
   * data types, the entire data point will be deleted. For data types where the
   * time span represents a consistent value (such as
   * com.google.activity.segment), and a data point straddles either end point of
   * the dataset, only the overlapping portion of the data point will be deleted.
   * (datasets.delete)
   *
   * @param string $userId Delete a dataset for the person identified. Use me to
   * indicate the authenticated user. Only me is supported at this time.
   * @param string $dataSourceId The data stream ID of the data source that
   * created the dataset.
   * @param string $datasetId Dataset identifier that is a composite of the
   * minimum data point start time and maximum data point end time represented as
   * nanoseconds from the epoch. The ID is formatted like: "startTime-endTime"
   * where startTime and endTime are 64 bit integers.
   * @param array $optParams Optional parameters.
   * @throws \Google\Service\Exception
   */
  public function delete($userId, $dataSourceId, $datasetId, $optParams = [])
  {
    $params = ['userId' => $userId, 'dataSourceId' => $dataSourceId, 'datasetId' => $datasetId];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params]);
  }
  /**
   * Returns a dataset containing all data points whose start and end times
   * overlap with the specified range of the dataset minimum start time and
   * maximum end time. Specifically, any data point whose start time is less than
   * or equal to the dataset end time and whose end time is greater than or equal
   * to the dataset start time. (datasets.get)
   *
   * @param string $userId Retrieve a dataset for the person identified. Use me to
   * indicate the authenticated user. Only me is supported at this time.
   * @param string $dataSourceId The data stream ID of the data source that
   * created the dataset.
   * @param string $datasetId Dataset identifier that is a composite of the
   * minimum data point start time and maximum data point end time represented as
   * nanoseconds from the epoch. The ID is formatted like: "startTime-endTime"
   * where startTime and endTime are 64 bit integers.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int limit If specified, no more than this many data points will be
   * included in the dataset. If there are more data points in the dataset,
   * nextPageToken will be set in the dataset response. The limit is applied from
   * the end of the time range. That is, if pageToken is absent, the limit most
   * recent data points will be returned.
   * @opt_param string pageToken The continuation token, which is used to page
   * through large datasets. To get the next page of a dataset, set this parameter
   * to the value of nextPageToken from the previous response. Each subsequent
   * call will yield a partial dataset with data point end timestamps that are
   * strictly smaller than those in the previous partial response.
   * @return Dataset
   * @throws \Google\Service\Exception
   */
  public function get($userId, $dataSourceId, $datasetId, $optParams = [])
  {
    $params = ['userId' => $userId, 'dataSourceId' => $dataSourceId, 'datasetId' => $datasetId];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Dataset::class);
  }
  /**
   * Adds data points to a dataset. The dataset need not be previously created.
   * All points within the given dataset will be returned with subsquent calls to
   * retrieve this dataset. Data points can belong to more than one dataset. This
   * method does not use patch semantics: the data points provided are merely
   * inserted, with no existing data replaced. (datasets.patch)
   *
   * @param string $userId Patch a dataset for the person identified. Use me to
   * indicate the authenticated user. Only me is supported at this time.
   * @param string $dataSourceId The data stream ID of the data source that
   * created the dataset.
   * @param string $datasetId This field is not used, and can be safely omitted.
   * @param Dataset $postBody
   * @param array $optParams Optional parameters.
   * @return Dataset
   * @throws \Google\Service\Exception
   */
  public function patch($userId, $dataSourceId, $datasetId, Dataset $postBody, $optParams = [])
  {
    $params = ['userId' => $userId, 'dataSourceId' => $dataSourceId, 'datasetId' => $datasetId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], Dataset::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(UsersDataSourcesDatasets::class, 'Google_Service_Fitness_Resource_UsersDataSourcesDatasets');
