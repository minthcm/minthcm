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

namespace Google\Service\WorkloadManager;

class ListScannedResourcesResponse extends \Google\Collection
{
  protected $collection_key = 'scannedResources';
  /**
   * @var string
   */
  public $nextPageToken;
  protected $scannedResourcesType = ScannedResource::class;
  protected $scannedResourcesDataType = 'array';

  /**
   * @param string
   */
  public function setNextPageToken($nextPageToken)
  {
    $this->nextPageToken = $nextPageToken;
  }
  /**
   * @return string
   */
  public function getNextPageToken()
  {
    return $this->nextPageToken;
  }
  /**
   * @param ScannedResource[]
   */
  public function setScannedResources($scannedResources)
  {
    $this->scannedResources = $scannedResources;
  }
  /**
   * @return ScannedResource[]
   */
  public function getScannedResources()
  {
    return $this->scannedResources;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ListScannedResourcesResponse::class, 'Google_Service_WorkloadManager_ListScannedResourcesResponse');
