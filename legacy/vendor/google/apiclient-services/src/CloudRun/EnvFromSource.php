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

namespace Google\Service\CloudRun;

class EnvFromSource extends \Google\Model
{
  protected $configMapRefType = ConfigMapEnvSource::class;
  protected $configMapRefDataType = '';
  /**
   * @var string
   */
  public $prefix;
  protected $secretRefType = SecretEnvSource::class;
  protected $secretRefDataType = '';

  /**
   * @param ConfigMapEnvSource
   */
  public function setConfigMapRef(ConfigMapEnvSource $configMapRef)
  {
    $this->configMapRef = $configMapRef;
  }
  /**
   * @return ConfigMapEnvSource
   */
  public function getConfigMapRef()
  {
    return $this->configMapRef;
  }
  /**
   * @param string
   */
  public function setPrefix($prefix)
  {
    $this->prefix = $prefix;
  }
  /**
   * @return string
   */
  public function getPrefix()
  {
    return $this->prefix;
  }
  /**
   * @param SecretEnvSource
   */
  public function setSecretRef(SecretEnvSource $secretRef)
  {
    $this->secretRef = $secretRef;
  }
  /**
   * @return SecretEnvSource
   */
  public function getSecretRef()
  {
    return $this->secretRef;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(EnvFromSource::class, 'Google_Service_CloudRun_EnvFromSource');
