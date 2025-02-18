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

namespace Google\Service\Container;

class LinuxNodeConfig extends \Google\Model
{
  /**
   * @var string
   */
  public $cgroupMode;
  protected $hugepagesType = HugepagesConfig::class;
  protected $hugepagesDataType = '';
  /**
   * @var string[]
   */
  public $sysctls;

  /**
   * @param string
   */
  public function setCgroupMode($cgroupMode)
  {
    $this->cgroupMode = $cgroupMode;
  }
  /**
   * @return string
   */
  public function getCgroupMode()
  {
    return $this->cgroupMode;
  }
  /**
   * @param HugepagesConfig
   */
  public function setHugepages(HugepagesConfig $hugepages)
  {
    $this->hugepages = $hugepages;
  }
  /**
   * @return HugepagesConfig
   */
  public function getHugepages()
  {
    return $this->hugepages;
  }
  /**
   * @param string[]
   */
  public function setSysctls($sysctls)
  {
    $this->sysctls = $sysctls;
  }
  /**
   * @return string[]
   */
  public function getSysctls()
  {
    return $this->sysctls;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(LinuxNodeConfig::class, 'Google_Service_Container_LinuxNodeConfig');
