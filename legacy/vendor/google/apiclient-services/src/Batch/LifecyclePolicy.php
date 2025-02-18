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

namespace Google\Service\Batch;

class LifecyclePolicy extends \Google\Model
{
  /**
   * @var string
   */
  public $action;
  protected $actionConditionType = ActionCondition::class;
  protected $actionConditionDataType = '';

  /**
   * @param string
   */
  public function setAction($action)
  {
    $this->action = $action;
  }
  /**
   * @return string
   */
  public function getAction()
  {
    return $this->action;
  }
  /**
   * @param ActionCondition
   */
  public function setActionCondition(ActionCondition $actionCondition)
  {
    $this->actionCondition = $actionCondition;
  }
  /**
   * @return ActionCondition
   */
  public function getActionCondition()
  {
    return $this->actionCondition;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(LifecyclePolicy::class, 'Google_Service_Batch_LifecyclePolicy');
