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

class PersonalApplicationPolicy extends \Google\Model
{
  /**
   * @var string
   */
  public $installType;
  /**
   * @var string
   */
  public $packageName;

  /**
   * @param string
   */
  public function setInstallType($installType)
  {
    $this->installType = $installType;
  }
  /**
   * @return string
   */
  public function getInstallType()
  {
    return $this->installType;
  }
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
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(PersonalApplicationPolicy::class, 'Google_Service_AndroidManagement_PersonalApplicationPolicy');
