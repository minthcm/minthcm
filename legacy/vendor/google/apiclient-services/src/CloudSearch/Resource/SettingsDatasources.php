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

namespace Google\Service\CloudSearch\Resource;

use Google\Service\CloudSearch\DataSource;
use Google\Service\CloudSearch\ListDataSourceResponse;
use Google\Service\CloudSearch\Operation;
use Google\Service\CloudSearch\UpdateDataSourceRequest;

/**
 * The "datasources" collection of methods.
 * Typical usage is:
 *  <code>
 *   $cloudsearchService = new Google\Service\CloudSearch(...);
 *   $datasources = $cloudsearchService->settings_datasources;
 *  </code>
 */
class SettingsDatasources extends \Google\Service\Resource
{
  /**
   * Creates a datasource. **Note:** This API requires an admin account to
   * execute. (datasources.create)
   *
   * @param DataSource $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function create(DataSource $postBody, $optParams = [])
  {
    $params = ['postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], Operation::class);
  }
  /**
   * Deletes a datasource. **Note:** This API requires an admin account to
   * execute. (datasources.delete)
   *
   * @param string $name The name of the datasource. Format:
   * datasources/{source_id}.
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool debugOptions.enableDebugging If you are asked by Google to
   * help with debugging, set this field. Otherwise, ignore this field.
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
   * Gets a datasource. **Note:** This API requires an admin account to execute.
   * (datasources.get)
   *
   * @param string $name The name of the datasource resource. Format:
   * datasources/{source_id}.
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool debugOptions.enableDebugging If you are asked by Google to
   * help with debugging, set this field. Otherwise, ignore this field.
   * @return DataSource
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], DataSource::class);
  }
  /**
   * Lists datasources. **Note:** This API requires an admin account to execute.
   * (datasources.listSettingsDatasources)
   *
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool debugOptions.enableDebugging If you are asked by Google to
   * help with debugging, set this field. Otherwise, ignore this field.
   * @opt_param int pageSize Maximum number of datasources to fetch in a request.
   * The max value is 1000. The default value is 1000.
   * @opt_param string pageToken Starting index of the results.
   * @return ListDataSourceResponse
   * @throws \Google\Service\Exception
   */
  public function listSettingsDatasources($optParams = [])
  {
    $params = [];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListDataSourceResponse::class);
  }
  /**
   * Updates a datasource. **Note:** This API requires an admin account to
   * execute. (datasources.patch)
   *
   * @param string $name The name of the datasource resource. Format:
   * datasources/{source_id}. The name is ignored when creating a datasource.
   * @param DataSource $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool debugOptions.enableDebugging If you are asked by Google to
   * help with debugging, set this field. Otherwise, ignore this field.
   * @opt_param string updateMask Only applies to
   * [`settings.datasources.patch`](https://developers.google.com/cloud-
   * search/docs/reference/rest/v1/settings.datasources/patch). Update mask to
   * control which fields to update. Example field paths: `name`, `displayName`. *
   * If `update_mask` is non-empty, then only the fields specified in the
   * `update_mask` are updated. * If you specify a field in the `update_mask`, but
   * don't specify its value in the source, that field is cleared. * If the
   * `update_mask` is not present or empty or has the value `*`, then all fields
   * are updated.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function patch($name, DataSource $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], Operation::class);
  }
  /**
   * Updates a datasource. **Note:** This API requires an admin account to
   * execute. (datasources.update)
   *
   * @param string $name The name of the datasource resource. Format:
   * datasources/{source_id}. The name is ignored when creating a datasource.
   * @param UpdateDataSourceRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function update($name, UpdateDataSourceRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('update', [$params], Operation::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SettingsDatasources::class, 'Google_Service_CloudSearch_Resource_SettingsDatasources');
