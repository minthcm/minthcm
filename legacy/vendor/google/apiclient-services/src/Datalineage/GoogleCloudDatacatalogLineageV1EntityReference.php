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

namespace Google\Service\Datalineage;

class GoogleCloudDatacatalogLineageV1EntityReference extends \Google\Model
{
  /**
   * @var string
   */
  public $fullyQualifiedName;

  /**
   * @param string
   */
  public function setFullyQualifiedName($fullyQualifiedName)
  {
    $this->fullyQualifiedName = $fullyQualifiedName;
  }
  /**
   * @return string
   */
  public function getFullyQualifiedName()
  {
    return $this->fullyQualifiedName;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDatacatalogLineageV1EntityReference::class, 'Google_Service_Datalineage_GoogleCloudDatacatalogLineageV1EntityReference');
