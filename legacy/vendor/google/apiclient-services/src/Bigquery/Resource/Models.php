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

namespace Google\Service\Bigquery\Resource;

use Google\Service\Bigquery\ListModelsResponse;
use Google\Service\Bigquery\Model;

/**
 * The "models" collection of methods.
 * Typical usage is:
 *  <code>
 *   $bigqueryService = new Google\Service\Bigquery(...);
 *   $models = $bigqueryService->models;
 *  </code>
 */
class Models extends \Google\Service\Resource
{
  /**
   * Deletes the model specified by modelId from the dataset. (models.delete)
   *
   * @param string $projectId Required. Project ID of the model to delete.
   * @param string $datasetId Required. Dataset ID of the model to delete.
   * @param string $modelId Required. Model ID of the model to delete.
   * @param array $optParams Optional parameters.
   * @throws \Google\Service\Exception
   */
  public function delete($projectId, $datasetId, $modelId, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'datasetId' => $datasetId, 'modelId' => $modelId];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params]);
  }
  /**
   * Gets the specified model resource by model ID. (models.get)
   *
   * @param string $projectId Required. Project ID of the requested model.
   * @param string $datasetId Required. Dataset ID of the requested model.
   * @param string $modelId Required. Model ID of the requested model.
   * @param array $optParams Optional parameters.
   * @return Model
   * @throws \Google\Service\Exception
   */
  public function get($projectId, $datasetId, $modelId, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'datasetId' => $datasetId, 'modelId' => $modelId];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Model::class);
  }
  /**
   * Lists all models in the specified dataset. Requires the READER dataset role.
   * After retrieving the list of models, you can get information about a
   * particular model by calling the models.get method. (models.listModels)
   *
   * @param string $projectId Required. Project ID of the models to list.
   * @param string $datasetId Required. Dataset ID of the models to list.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string maxResults The maximum number of results to return in a
   * single response page. Leverage the page tokens to iterate through the entire
   * collection.
   * @opt_param string pageToken Page token, returned by a previous call to
   * request the next page of results
   * @return ListModelsResponse
   * @throws \Google\Service\Exception
   */
  public function listModels($projectId, $datasetId, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'datasetId' => $datasetId];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListModelsResponse::class);
  }
  /**
   * Patch specific fields in the specified model. (models.patch)
   *
   * @param string $projectId Required. Project ID of the model to patch.
   * @param string $datasetId Required. Dataset ID of the model to patch.
   * @param string $modelId Required. Model ID of the model to patch.
   * @param Model $postBody
   * @param array $optParams Optional parameters.
   * @return Model
   * @throws \Google\Service\Exception
   */
  public function patch($projectId, $datasetId, $modelId, Model $postBody, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'datasetId' => $datasetId, 'modelId' => $modelId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], Model::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Models::class, 'Google_Service_Bigquery_Resource_Models');
