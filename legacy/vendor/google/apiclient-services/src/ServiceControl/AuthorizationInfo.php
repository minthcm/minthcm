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

namespace Google\Service\ServiceControl;

class AuthorizationInfo extends \Google\Model
{
  /**
   * @var bool
   */
  public $granted;
  /**
   * @var string
   */
  public $permission;
  /**
   * @var string
   */
  public $permissionType;
  /**
   * @var string
   */
  public $resource;
  protected $resourceAttributesType = ServicecontrolResource::class;
  protected $resourceAttributesDataType = '';

  /**
   * @param bool
   */
  public function setGranted($granted)
  {
    $this->granted = $granted;
  }
  /**
   * @return bool
   */
  public function getGranted()
  {
    return $this->granted;
  }
  /**
   * @param string
   */
  public function setPermission($permission)
  {
    $this->permission = $permission;
  }
  /**
   * @return string
   */
  public function getPermission()
  {
    return $this->permission;
  }
  /**
   * @param string
   */
  public function setPermissionType($permissionType)
  {
    $this->permissionType = $permissionType;
  }
  /**
   * @return string
   */
  public function getPermissionType()
  {
    return $this->permissionType;
  }
  /**
   * @param string
   */
  public function setResource($resource)
  {
    $this->resource = $resource;
  }
  /**
   * @return string
   */
  public function getResource()
  {
    return $this->resource;
  }
  /**
   * @param ServicecontrolResource
   */
  public function setResourceAttributes(ServicecontrolResource $resourceAttributes)
  {
    $this->resourceAttributes = $resourceAttributes;
  }
  /**
   * @return ServicecontrolResource
   */
  public function getResourceAttributes()
  {
    return $this->resourceAttributes;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AuthorizationInfo::class, 'Google_Service_ServiceControl_AuthorizationInfo');
