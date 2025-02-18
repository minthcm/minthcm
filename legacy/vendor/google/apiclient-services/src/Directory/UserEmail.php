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

class UserEmail extends \Google\Model
{
  protected $internal_gapi_mappings = [
        "publicKeyEncryptionCertificates" => "public_key_encryption_certificates",
  ];
  /**
   * @var string
   */
  public $address;
  /**
   * @var string
   */
  public $customType;
  /**
   * @var bool
   */
  public $primary;
  protected $publicKeyEncryptionCertificatesType = UserEmailPublicKeyEncryptionCertificates::class;
  protected $publicKeyEncryptionCertificatesDataType = '';
  /**
   * @var string
   */
  public $type;

  /**
   * @param string
   */
  public function setAddress($address)
  {
    $this->address = $address;
  }
  /**
   * @return string
   */
  public function getAddress()
  {
    return $this->address;
  }
  /**
   * @param string
   */
  public function setCustomType($customType)
  {
    $this->customType = $customType;
  }
  /**
   * @return string
   */
  public function getCustomType()
  {
    return $this->customType;
  }
  /**
   * @param bool
   */
  public function setPrimary($primary)
  {
    $this->primary = $primary;
  }
  /**
   * @return bool
   */
  public function getPrimary()
  {
    return $this->primary;
  }
  /**
   * @param UserEmailPublicKeyEncryptionCertificates
   */
  public function setPublicKeyEncryptionCertificates(UserEmailPublicKeyEncryptionCertificates $publicKeyEncryptionCertificates)
  {
    $this->publicKeyEncryptionCertificates = $publicKeyEncryptionCertificates;
  }
  /**
   * @return UserEmailPublicKeyEncryptionCertificates
   */
  public function getPublicKeyEncryptionCertificates()
  {
    return $this->publicKeyEncryptionCertificates;
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
class_alias(UserEmail::class, 'Google_Service_Directory_UserEmail');
