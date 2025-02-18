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

class WipeAction extends \Google\Model
{
  /**
   * @var bool
   */
  public $preserveFrp;
  /**
   * @var int
   */
  public $wipeAfterDays;

  /**
   * @param bool
   */
  public function setPreserveFrp($preserveFrp)
  {
    $this->preserveFrp = $preserveFrp;
  }
  /**
   * @return bool
   */
  public function getPreserveFrp()
  {
    return $this->preserveFrp;
  }
  /**
   * @param int
   */
  public function setWipeAfterDays($wipeAfterDays)
  {
    $this->wipeAfterDays = $wipeAfterDays;
  }
  /**
   * @return int
   */
  public function getWipeAfterDays()
  {
    return $this->wipeAfterDays;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(WipeAction::class, 'Google_Service_AndroidManagement_WipeAction');
