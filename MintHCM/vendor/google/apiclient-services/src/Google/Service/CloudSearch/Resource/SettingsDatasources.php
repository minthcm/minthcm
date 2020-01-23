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

/**
 * The "datasources" collection of methods.
 * Typical usage is:
 *  <code>
 *   $cloudsearchService = new Google_Service_CloudSearch(...);
 *   $datasources = $cloudsearchService->datasources;
 *  </code>
 */
class Google_Service_CloudSearch_Resource_SettingsDatasources extends Google_Service_Resource
{
  /**
   * Creates data source. (datasources.create)
   *
   * @param Google_Service_CloudSearch_DataSource $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_CloudSearch_Operation
   */
  public function create(Google_Service_CloudSearch_DataSource $postBody, $optParams = array())
  {
    $params = array('postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('create', array($params), "Google_Service_CloudSearch_Operation");
  }
  /**
   * Deletes a data source. (datasources.delete)
   *
   * @param string $name Name of the data source. Format: datasources/{source_id}.
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool debugOptions.enableDebugging If set, the request will enable
   * debugging features of Cloud Search. Only turn on this field, if asked by
   * Google to help with debugging.
   * @return Google_Service_CloudSearch_Operation
   */
  public function delete($name, $optParams = array())
  {
    $params = array('name' => $name);
    $params = array_merge($params, $optParams);
    return $this->call('delete', array($params), "Google_Service_CloudSearch_Operation");
  }
  /**
   * Gets a data source. (datasources.get)
   *
   * @param string $name Name of the data source resource. Format:
   * datasources/{source_id}.
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool debugOptions.enableDebugging If set, the request will enable
   * debugging features of Cloud Search. Only turn on this field, if asked by
   * Google to help with debugging.
   * @return Google_Service_CloudSearch_DataSource
   */
  public function get($name, $optParams = array())
  {
    $params = array('name' => $name);
    $params = array_merge($params, $optParams);
    return $this->call('get', array($params), "Google_Service_CloudSearch_DataSource");
  }
  /**
   * Lists data sources. (datasources.listSettingsDatasources)
   *
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool debugOptions.enableDebugging If set, the request will enable
   * debugging features of Cloud Search. Only turn on this field, if asked by
   * Google to help with debugging.
   * @opt_param string pageToken Starting index of the results.
   * @opt_param int pageSize Maximum number of data sources to fetch in a request.
   * The max value is 100. The default value is 10
   * @return Google_Service_CloudSearch_ListDataSourceResponse
   */
  public function listSettingsDatasources($optParams = array())
  {
    $params = array();
    $params = array_merge($params, $optParams);
    return $this->call('list', array($params), "Google_Service_CloudSearch_ListDataSourceResponse");
  }
  /**
   * Updates a data source. (datasources.update)
   *
   * @param string $name Name of the data source resource. Format:
   * datasources/{source_id}. The name is ignored when creating a data source.
   * @param Google_Service_CloudSearch_UpdateDataSourceRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_CloudSearch_Operation
   */
  public function update($name, Google_Service_CloudSearch_UpdateDataSourceRequest $postBody, $optParams = array())
  {
    $params = array('name' => $name, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('update', array($params), "Google_Service_CloudSearch_Operation");
  }
}
