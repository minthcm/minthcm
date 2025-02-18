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

namespace Google\Service\PublicCertificateAuthority;

class ExternalAccountKey extends \Google\Model
{
  /**
   * @var string
   */
  public $b64MacKey;
  /**
   * @var string
   */
  public $keyId;
  /**
   * @var string
   */
  public $name;

  /**
   * @param string
   */
  public function setB64MacKey($b64MacKey)
  {
    $this->b64MacKey = $b64MacKey;
  }
  /**
   * @return string
   */
  public function getB64MacKey()
  {
    return $this->b64MacKey;
  }
  /**
   * @param string
   */
  public function setKeyId($keyId)
  {
    $this->keyId = $keyId;
  }
  /**
   * @return string
   */
  public function getKeyId()
  {
    return $this->keyId;
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
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ExternalAccountKey::class, 'Google_Service_PublicCertificateAuthority_ExternalAccountKey');
