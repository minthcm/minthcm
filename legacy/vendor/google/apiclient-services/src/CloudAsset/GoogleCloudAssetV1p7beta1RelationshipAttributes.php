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

namespace Google\Service\CloudAsset;

class GoogleCloudAssetV1p7beta1RelationshipAttributes extends \Google\Model
{
  /**
   * @var string
   */
  public $action;
  /**
   * @var string
   */
  public $sourceResourceType;
  /**
   * @var string
   */
  public $targetResourceType;
  /**
   * @var string
   */
  public $type;

  /**
   * @param string
   */
  public function setAction($action)
  {
    $this->action = $action;
  }
  /**
   * @return string
   */
  public function getAction()
  {
    return $this->action;
  }
  /**
   * @param string
   */
  public function setSourceResourceType($sourceResourceType)
  {
    $this->sourceResourceType = $sourceResourceType;
  }
  /**
   * @return string
   */
  public function getSourceResourceType()
  {
    return $this->sourceResourceType;
  }
  /**
   * @param string
   */
  public function setTargetResourceType($targetResourceType)
  {
    $this->targetResourceType = $targetResourceType;
  }
  /**
   * @return string
   */
  public function getTargetResourceType()
  {
    return $this->targetResourceType;
  }
  /**
   * @param string
   */
  public function setType($type)
  {
    $this->type = $type;
  }
  /**
   * @return string
   */
  public function getType()
  {
    return $this->type;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAssetV1p7beta1RelationshipAttributes::class, 'Google_Service_CloudAsset_GoogleCloudAssetV1p7beta1RelationshipAttributes');
