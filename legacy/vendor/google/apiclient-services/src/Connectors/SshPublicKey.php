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

namespace Google\Service\Connectors;

class SshPublicKey extends \Google\Model
{
  /**
   * @var string
   */
  public $certType;
  protected $passwordType = Secret::class;
  protected $passwordDataType = '';
  protected $sshClientCertType = Secret::class;
  protected $sshClientCertDataType = '';
  protected $sshClientCertPassType = Secret::class;
  protected $sshClientCertPassDataType = '';
  /**
   * @var string
   */
  public $username;

  /**
   * @param string
   */
  public function setCertType($certType)
  {
    $this->certType = $certType;
  }
  /**
   * @return string
   */
  public function getCertType()
  {
    return $this->certType;
  }
  /**
   * @param Secret
   */
  public function setPassword(Secret $password)
  {
    $this->password = $password;
  }
  /**
   * @return Secret
   */
  public function getPassword()
  {
    return $this->password;
  }
  /**
   * @param Secret
   */
  public function setSshClientCert(Secret $sshClientCert)
  {
    $this->sshClientCert = $sshClientCert;
  }
  /**
   * @return Secret
   */
  public function getSshClientCert()
  {
    return $this->sshClientCert;
  }
  /**
   * @param Secret
   */
  public function setSshClientCertPass(Secret $sshClientCertPass)
  {
    $this->sshClientCertPass = $sshClientCertPass;
  }
  /**
   * @return Secret
   */
  public function getSshClientCertPass()
  {
    return $this->sshClientCertPass;
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
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SshPublicKey::class, 'Google_Service_Connectors_SshPublicKey');
