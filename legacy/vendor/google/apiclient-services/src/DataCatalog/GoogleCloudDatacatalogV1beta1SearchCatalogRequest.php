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

namespace Google\Service\DataCatalog;

class GoogleCloudDatacatalogV1beta1SearchCatalogRequest extends \Google\Model
{
  public $orderBy;
  public $pageSize;
  public $pageToken;
  public $query;
  protected $scopeType = GoogleCloudDatacatalogV1beta1SearchCatalogRequestScope::class;
  protected $scopeDataType = '';

  public function setOrderBy($orderBy)
  {
    $this->orderBy = $orderBy;
  }
  public function getOrderBy()
  {
    return $this->orderBy;
  }
  public function setPageSize($pageSize)
  {
    $this->pageSize = $pageSize;
  }
  public function getPageSize()
  {
    return $this->pageSize;
  }
  public function setPageToken($pageToken)
  {
    $this->pageToken = $pageToken;
  }
  public function getPageToken()
  {
    return $this->pageToken;
  }
  public function setQuery($query)
  {
    $this->query = $query;
  }
  public function getQuery()
  {
    return $this->query;
  }
  /**
   * @param GoogleCloudDatacatalogV1beta1SearchCatalogRequestScope
   */
  public function setScope(GoogleCloudDatacatalogV1beta1SearchCatalogRequestScope $scope)
  {
    $this->scope = $scope;
  }
  /**
   * @return GoogleCloudDatacatalogV1beta1SearchCatalogRequestScope
   */
  public function getScope()
  {
    return $this->scope;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDatacatalogV1beta1SearchCatalogRequest::class, 'Google_Service_DataCatalog_GoogleCloudDatacatalogV1beta1SearchCatalogRequest');
