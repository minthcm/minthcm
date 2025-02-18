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

class ManagedConfigurationTemplate extends \Google\Model
{
  /**
   * @var string[]
   */
  public $configurationVariables;
  /**
   * @var string
   */
  public $templateId;

  /**
   * @param string[]
   */
  public function setConfigurationVariables($configurationVariables)
  {
    $this->configurationVariables = $configurationVariables;
  }
  /**
   * @return string[]
   */
  public function getConfigurationVariables()
  {
    return $this->configurationVariables;
  }
  /**
   * @param string
   */
  public function setTemplateId($templateId)
  {
    $this->templateId = $templateId;
  }
  /**
   * @return string
   */
  public function getTemplateId()
  {
    return $this->templateId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ManagedConfigurationTemplate::class, 'Google_Service_AndroidManagement_ManagedConfigurationTemplate');
