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

namespace Google\Service\VMMigrationService;

class VmwareSourceDetails extends \Google\Model
{
  /**
   * @var string
   */
  public $password;
  /**
   * @var string
   */
  public $resolvedVcenterHost;
  /**
   * @var string
   */
  public $thumbprint;
  /**
   * @var string
   */
  public $username;
  /**
   * @var string
   */
  public $vcenterIp;

  /**
   * @param string
   */
  public function setPassword($password)
  {
    $this->password = $password;
  }
  /**
   * @return string
   */
  public function getPassword()
  {
    return $this->password;
  }
  /**
   * @param string
   */
  public function setResolvedVcenterHost($resolvedVcenterHost)
  {
    $this->resolvedVcenterHost = $resolvedVcenterHost;
  }
  /**
   * @return string
   */
  public function getResolvedVcenterHost()
  {
    return $this->resolvedVcenterHost;
  }
  /**
   * @param string
   */
  public function setThumbprint($thumbprint)
  {
    $this->thumbprint = $thumbprint;
  }
  /**
   * @return string
   */
  public function getThumbprint()
  {
    return $this->thumbprint;
  }
  /**
   * @param string
   */
  public function setUsername($username)
  {
    $this->username = $username;
  }
  /**
   * @return string
   */
  public function getUsername()
  {
    return $this->username;
  }
  /**
   * @param string
   */
  public function setVcenterIp($vcenterIp)
  {
    $this->vcenterIp = $vcenterIp;
  }
  /**
   * @return string
   */
  public function getVcenterIp()
  {
    return $this->vcenterIp;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(VmwareSourceDetails::class, 'Google_Service_VMMigrationService_VmwareSourceDetails');
