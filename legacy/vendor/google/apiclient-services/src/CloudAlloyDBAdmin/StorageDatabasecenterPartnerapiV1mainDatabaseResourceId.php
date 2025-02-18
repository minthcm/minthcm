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

namespace Google\Service\CloudAlloyDBAdmin;

class StorageDatabasecenterPartnerapiV1mainDatabaseResourceId extends \Google\Model
{
  /**
   * @var string
   */
  public $provider;
  /**
   * @var string
   */
  public $providerDescription;
  /**
   * @var string
   */
  public $resourceType;
  /**
   * @var string
   */
  public $uniqueId;

  /**
   * @param string
   */
  public function setProvider($provider)
  {
    $this->provider = $provider;
  }
  /**
   * @return string
   */
  public function getProvider()
  {
    return $this->provider;
  }
  /**
   * @param string
   */
  public function setProviderDescription($providerDescription)
  {
    $this->providerDescription = $providerDescription;
  }
  /**
   * @return string
   */
  public function getProviderDescription()
  {
    return $this->providerDescription;
  }
  /**
   * @param string
   */
  public function setResourceType($resourceType)
  {
    $this->resourceType = $resourceType;
  }
  /**
   * @return string
   */
  public function getResourceType()
  {
    return $this->resourceType;
  }
  /**
   * @param string
   */
  public function setUniqueId($uniqueId)
  {
    $this->uniqueId = $uniqueId;
  }
  /**
   * @return string
   */
  public function getUniqueId()
  {
    return $this->uniqueId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(StorageDatabasecenterPartnerapiV1mainDatabaseResourceId::class, 'Google_Service_CloudAlloyDBAdmin_StorageDatabasecenterPartnerapiV1mainDatabaseResourceId');
