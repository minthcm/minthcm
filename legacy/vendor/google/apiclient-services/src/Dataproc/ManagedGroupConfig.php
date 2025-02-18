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

namespace Google\Service\Dataproc;

class ManagedGroupConfig extends \Google\Model
{
  /**
   * @var string
   */
  public $instanceGroupManagerName;
  /**
   * @var string
   */
  public $instanceGroupManagerUri;
  /**
   * @var string
   */
  public $instanceTemplateName;

  /**
   * @param string
   */
  public function setInstanceGroupManagerName($instanceGroupManagerName)
  {
    $this->instanceGroupManagerName = $instanceGroupManagerName;
  }
  /**
   * @return string
   */
  public function getInstanceGroupManagerName()
  {
    return $this->instanceGroupManagerName;
  }
  /**
   * @param string
   */
  public function setInstanceGroupManagerUri($instanceGroupManagerUri)
  {
    $this->instanceGroupManagerUri = $instanceGroupManagerUri;
  }
  /**
   * @return string
   */
  public function getInstanceGroupManagerUri()
  {
    return $this->instanceGroupManagerUri;
  }
  /**
   * @param string
   */
  public function setInstanceTemplateName($instanceTemplateName)
  {
    $this->instanceTemplateName = $instanceTemplateName;
  }
  /**
   * @return string
   */
  public function getInstanceTemplateName()
  {
    return $this->instanceTemplateName;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ManagedGroupConfig::class, 'Google_Service_Dataproc_ManagedGroupConfig');
