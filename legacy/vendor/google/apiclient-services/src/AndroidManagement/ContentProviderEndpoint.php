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

namespace Google\Service\AndroidManagement;

class ContentProviderEndpoint extends \Google\Collection
{
  protected $collection_key = 'signingCertsSha256';
  /**
   * @var string
   */
  public $packageName;
  /**
   * @var string[]
   */
  public $signingCertsSha256;
  /**
   * @var string
   */
  public $uri;

  /**
   * @param string
   */
  public function setPackageName($packageName)
  {
    $this->packageName = $packageName;
  }
  /**
   * @return string
   */
  public function getPackageName()
  {
    return $this->packageName;
  }
  /**
   * @param string[]
   */
  public function setSigningCertsSha256($signingCertsSha256)
  {
    $this->signingCertsSha256 = $signingCertsSha256;
  }
  /**
   * @return string[]
   */
  public function getSigningCertsSha256()
  {
    return $this->signingCertsSha256;
  }
  /**
   * @param string
   */
  public function setUri($uri)
  {
    $this->uri = $uri;
  }
  /**
   * @return string
   */
  public function getUri()
  {
    return $this->uri;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ContentProviderEndpoint::class, 'Google_Service_AndroidManagement_ContentProviderEndpoint');
