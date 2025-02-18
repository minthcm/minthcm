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

namespace Google\Service\CloudIdentity;

class IdpCredential extends \Google\Model
{
  protected $dsaKeyInfoType = DsaPublicKeyInfo::class;
  protected $dsaKeyInfoDataType = '';
  /**
   * @var string
   */
  public $name;
  protected $rsaKeyInfoType = RsaPublicKeyInfo::class;
  protected $rsaKeyInfoDataType = '';
  /**
   * @var string
   */
  public $updateTime;

  /**
   * @param DsaPublicKeyInfo
   */
  public function setDsaKeyInfo(DsaPublicKeyInfo $dsaKeyInfo)
  {
    $this->dsaKeyInfo = $dsaKeyInfo;
  }
  /**
   * @return DsaPublicKeyInfo
   */
  public function getDsaKeyInfo()
  {
    return $this->dsaKeyInfo;
  }
  /**
   * @param string
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
  /**
   * @param RsaPublicKeyInfo
   */
  public function setRsaKeyInfo(RsaPublicKeyInfo $rsaKeyInfo)
  {
    $this->rsaKeyInfo = $rsaKeyInfo;
  }
  /**
   * @return RsaPublicKeyInfo
   */
  public function getRsaKeyInfo()
  {
    return $this->rsaKeyInfo;
  }
  /**
   * @param string
   */
  public function setUpdateTime($updateTime)
  {
    $this->updateTime = $updateTime;
  }
  /**
   * @return string
   */
  public function getUpdateTime()
  {
    return $this->updateTime;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(IdpCredential::class, 'Google_Service_CloudIdentity_IdpCredential');
