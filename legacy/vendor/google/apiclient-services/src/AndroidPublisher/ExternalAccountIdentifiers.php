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

namespace Google\Service\AndroidPublisher;

class ExternalAccountIdentifiers extends \Google\Model
{
  /**
   * @var string
   */
  public $externalAccountId;
  /**
   * @var string
   */
  public $obfuscatedExternalAccountId;
  /**
   * @var string
   */
  public $obfuscatedExternalProfileId;

  /**
   * @param string
   */
  public function setExternalAccountId($externalAccountId)
  {
    $this->externalAccountId = $externalAccountId;
  }
  /**
   * @return string
   */
  public function getExternalAccountId()
  {
    return $this->externalAccountId;
  }
  /**
   * @param string
   */
  public function setObfuscatedExternalAccountId($obfuscatedExternalAccountId)
  {
    $this->obfuscatedExternalAccountId = $obfuscatedExternalAccountId;
  }
  /**
   * @return string
   */
  public function getObfuscatedExternalAccountId()
  {
    return $this->obfuscatedExternalAccountId;
  }
  /**
   * @param string
   */
  public function setObfuscatedExternalProfileId($obfuscatedExternalProfileId)
  {
    $this->obfuscatedExternalProfileId = $obfuscatedExternalProfileId;
  }
  /**
   * @return string
   */
  public function getObfuscatedExternalProfileId()
  {
    return $this->obfuscatedExternalProfileId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ExternalAccountIdentifiers::class, 'Google_Service_AndroidPublisher_ExternalAccountIdentifiers');
