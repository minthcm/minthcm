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

namespace Google\Service\AndroidEnterprise;

class AutoInstallPolicy extends \Google\Collection
{
  protected $collection_key = 'autoInstallConstraint';
  protected $autoInstallConstraintType = AutoInstallConstraint::class;
  protected $autoInstallConstraintDataType = 'array';
  /**
   * @var string
   */
  public $autoInstallMode;
  /**
   * @var int
   */
  public $autoInstallPriority;
  /**
   * @var int
   */
  public $minimumVersionCode;

  /**
   * @param AutoInstallConstraint[]
   */
  public function setAutoInstallConstraint($autoInstallConstraint)
  {
    $this->autoInstallConstraint = $autoInstallConstraint;
  }
  /**
   * @return AutoInstallConstraint[]
   */
  public function getAutoInstallConstraint()
  {
    return $this->autoInstallConstraint;
  }
  /**
   * @param string
   */
  public function setAutoInstallMode($autoInstallMode)
  {
    $this->autoInstallMode = $autoInstallMode;
  }
  /**
   * @return string
   */
  public function getAutoInstallMode()
  {
    return $this->autoInstallMode;
  }
  /**
   * @param int
   */
  public function setAutoInstallPriority($autoInstallPriority)
  {
    $this->autoInstallPriority = $autoInstallPriority;
  }
  /**
   * @return int
   */
  public function getAutoInstallPriority()
  {
    return $this->autoInstallPriority;
  }
  /**
   * @param int
   */
  public function setMinimumVersionCode($minimumVersionCode)
  {
    $this->minimumVersionCode = $minimumVersionCode;
  }
  /**
   * @return int
   */
  public function getMinimumVersionCode()
  {
    return $this->minimumVersionCode;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AutoInstallPolicy::class, 'Google_Service_AndroidEnterprise_AutoInstallPolicy');
