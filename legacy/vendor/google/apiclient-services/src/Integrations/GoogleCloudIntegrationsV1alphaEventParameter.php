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

namespace Google\Service\Integrations;

class GoogleCloudIntegrationsV1alphaEventParameter extends \Google\Model
{
  /**
   * @var string
   */
  public $key;
  /**
   * @var bool
   */
  public $masked;
  protected $valueType = GoogleCloudIntegrationsV1alphaValueType::class;
  protected $valueDataType = '';

  /**
   * @param string
   */
  public function setKey($key)
  {
    $this->key = $key;
  }
  /**
   * @return string
   */
  public function getKey()
  {
    return $this->key;
  }
  /**
   * @param bool
   */
  public function setMasked($masked)
  {
    $this->masked = $masked;
  }
  /**
   * @return bool
   */
  public function getMasked()
  {
    return $this->masked;
  }
  /**
   * @param GoogleCloudIntegrationsV1alphaValueType
   */
  public function setValue(GoogleCloudIntegrationsV1alphaValueType $value)
  {
    $this->value = $value;
  }
  /**
   * @return GoogleCloudIntegrationsV1alphaValueType
   */
  public function getValue()
  {
    return $this->value;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudIntegrationsV1alphaEventParameter::class, 'Google_Service_Integrations_GoogleCloudIntegrationsV1alphaEventParameter');
