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

namespace Google\Service\NetworkServices;

class ListLbRouteExtensionsResponse extends \Google\Collection
{
  protected $collection_key = 'unreachable';
  protected $lbRouteExtensionsType = LbRouteExtension::class;
  protected $lbRouteExtensionsDataType = 'array';
  /**
   * @var string
   */
  public $nextPageToken;
  /**
   * @var string[]
   */
  public $unreachable;

  /**
   * @param LbRouteExtension[]
   */
  public function setLbRouteExtensions($lbRouteExtensions)
  {
    $this->lbRouteExtensions = $lbRouteExtensions;
  }
  /**
   * @return LbRouteExtension[]
   */
  public function getLbRouteExtensions()
  {
    return $this->lbRouteExtensions;
  }
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
   * @param string[]
   */
  public function setUnreachable($unreachable)
  {
    $this->unreachable = $unreachable;
  }
  /**
   * @return string[]
   */
  public function getUnreachable()
  {
    return $this->unreachable;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ListLbRouteExtensionsResponse::class, 'Google_Service_NetworkServices_ListLbRouteExtensionsResponse');
