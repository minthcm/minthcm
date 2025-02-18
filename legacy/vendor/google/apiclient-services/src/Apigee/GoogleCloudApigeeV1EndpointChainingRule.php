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

namespace Google\Service\Apigee;

class GoogleCloudApigeeV1EndpointChainingRule extends \Google\Collection
{
  protected $collection_key = 'proxyIds';
  /**
   * @var string
   */
  public $deploymentGroup;
  /**
   * @var string[]
   */
  public $proxyIds;

  /**
   * @param string
   */
  public function setDeploymentGroup($deploymentGroup)
  {
    $this->deploymentGroup = $deploymentGroup;
  }
  /**
   * @return string
   */
  public function getDeploymentGroup()
  {
    return $this->deploymentGroup;
  }
  /**
   * @param string[]
   */
  public function setProxyIds($proxyIds)
  {
    $this->proxyIds = $proxyIds;
  }
  /**
   * @return string[]
   */
  public function getProxyIds()
  {
    return $this->proxyIds;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudApigeeV1EndpointChainingRule::class, 'Google_Service_Apigee_GoogleCloudApigeeV1EndpointChainingRule');
