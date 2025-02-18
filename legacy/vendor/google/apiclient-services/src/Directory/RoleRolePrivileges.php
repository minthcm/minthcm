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

namespace Google\Service\Directory;

class RoleRolePrivileges extends \Google\Model
{
  /**
   * @var string
   */
  public $privilegeName;
  /**
   * @var string
   */
  public $serviceId;

  /**
   * @param string
   */
  public function setPrivilegeName($privilegeName)
  {
    $this->privilegeName = $privilegeName;
  }
  /**
   * @return string
   */
  public function getPrivilegeName()
  {
    return $this->privilegeName;
  }
  /**
   * @param string
   */
  public function setServiceId($serviceId)
  {
    $this->serviceId = $serviceId;
  }
  /**
   * @return string
   */
  public function getServiceId()
  {
    return $this->serviceId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(RoleRolePrivileges::class, 'Google_Service_Directory_RoleRolePrivileges');
