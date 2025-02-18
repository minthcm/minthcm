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

namespace Google\Service\ContainerAnalysis;

class Signature extends \Google\Model
{
  /**
   * @var string
   */
  public $publicKeyId;
  /**
   * @var string
   */
  public $signature;

  /**
   * @param string
   */
  public function setPublicKeyId($publicKeyId)
  {
    $this->publicKeyId = $publicKeyId;
  }
  /**
   * @return string
   */
  public function getPublicKeyId()
  {
    return $this->publicKeyId;
  }
  /**
   * @param string
   */
  public function setSignature($signature)
  {
    $this->signature = $signature;
  }
  /**
   * @return string
   */
  public function getSignature()
  {
    return $this->signature;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Signature::class, 'Google_Service_ContainerAnalysis_Signature');
