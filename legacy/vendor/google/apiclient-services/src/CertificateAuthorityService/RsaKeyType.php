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

namespace Google\Service\CertificateAuthorityService;

class RsaKeyType extends \Google\Model
{
  /**
   * @var string
   */
  public $maxModulusSize;
  /**
   * @var string
   */
  public $minModulusSize;

  /**
   * @param string
   */
  public function setMaxModulusSize($maxModulusSize)
  {
    $this->maxModulusSize = $maxModulusSize;
  }
  /**
   * @return string
   */
  public function getMaxModulusSize()
  {
    return $this->maxModulusSize;
  }
  /**
   * @param string
   */
  public function setMinModulusSize($minModulusSize)
  {
    $this->minModulusSize = $minModulusSize;
  }
  /**
   * @return string
   */
  public function getMinModulusSize()
  {
    return $this->minModulusSize;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(RsaKeyType::class, 'Google_Service_CertificateAuthorityService_RsaKeyType');
