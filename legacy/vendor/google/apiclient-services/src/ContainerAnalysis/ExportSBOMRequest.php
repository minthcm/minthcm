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

namespace Google\Service\ContainerAnalysis;

class ExportSBOMRequest extends \Google\Model
{
  protected $cloudStorageLocationType = CloudStorageLocation::class;
  protected $cloudStorageLocationDataType = '';

  /**
   * @param CloudStorageLocation
   */
  public function setCloudStorageLocation(CloudStorageLocation $cloudStorageLocation)
  {
    $this->cloudStorageLocation = $cloudStorageLocation;
  }
  /**
   * @return CloudStorageLocation
   */
  public function getCloudStorageLocation()
  {
    return $this->cloudStorageLocation;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ExportSBOMRequest::class, 'Google_Service_ContainerAnalysis_ExportSBOMRequest');
