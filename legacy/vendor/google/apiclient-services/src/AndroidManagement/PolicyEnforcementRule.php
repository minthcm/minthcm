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

class PolicyEnforcementRule extends \Google\Model
{
  protected $blockActionType = BlockAction::class;
  protected $blockActionDataType = '';
  /**
   * @var string
   */
  public $settingName;
  protected $wipeActionType = WipeAction::class;
  protected $wipeActionDataType = '';

  /**
   * @param BlockAction
   */
  public function setBlockAction(BlockAction $blockAction)
  {
    $this->blockAction = $blockAction;
  }
  /**
   * @return BlockAction
   */
  public function getBlockAction()
  {
    return $this->blockAction;
  }
  /**
   * @param string
   */
  public function setSettingName($settingName)
  {
    $this->settingName = $settingName;
  }
  /**
   * @return string
   */
  public function getSettingName()
  {
    return $this->settingName;
  }
  /**
   * @param WipeAction
   */
  public function setWipeAction(WipeAction $wipeAction)
  {
    $this->wipeAction = $wipeAction;
  }
  /**
   * @return WipeAction
   */
  public function getWipeAction()
  {
    return $this->wipeAction;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(PolicyEnforcementRule::class, 'Google_Service_AndroidManagement_PolicyEnforcementRule');
