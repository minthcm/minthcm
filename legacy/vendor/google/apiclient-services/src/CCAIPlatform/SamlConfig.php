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

namespace Google\Service\CCAIPlatform;

class SamlConfig extends \Google\Model
{
  /**
   * @var string
   */
  public $cert;
  /**
   * @var string
   */
  public $emailMapping;
  /**
   * @var string
   */
  public $entityId;
  /**
   * @var string
   */
  public $loginUri;

  /**
   * @param string
   */
  public function setCert($cert)
  {
    $this->cert = $cert;
  }
  /**
   * @return string
   */
  public function getCert()
  {
    return $this->cert;
  }
  /**
   * @param string
   */
  public function setEmailMapping($emailMapping)
  {
    $this->emailMapping = $emailMapping;
  }
  /**
   * @return string
   */
  public function getEmailMapping()
  {
    return $this->emailMapping;
  }
  /**
   * @param string
   */
  public function setEntityId($entityId)
  {
    $this->entityId = $entityId;
  }
  /**
   * @return string
   */
  public function getEntityId()
  {
    return $this->entityId;
  }
  /**
   * @param string
   */
  public function setLoginUri($loginUri)
  {
    $this->loginUri = $loginUri;
  }
  /**
   * @return string
   */
  public function getLoginUri()
  {
    return $this->loginUri;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SamlConfig::class, 'Google_Service_CCAIPlatform_SamlConfig');
