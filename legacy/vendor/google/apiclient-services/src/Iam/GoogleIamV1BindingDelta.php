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

namespace Google\Service\Iam;

class GoogleIamV1BindingDelta extends \Google\Model
{
  /**
   * @var string
   */
  public $action;
  protected $conditionType = GoogleTypeExpr::class;
  protected $conditionDataType = '';
  /**
   * @var string
   */
  public $member;
  /**
   * @var string
   */
  public $role;

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
   * @param GoogleTypeExpr
   */
  public function setCondition(GoogleTypeExpr $condition)
  {
    $this->condition = $condition;
  }
  /**
   * @return GoogleTypeExpr
   */
  public function getCondition()
  {
    return $this->condition;
  }
  /**
   * @param string
   */
  public function setMember($member)
  {
    $this->member = $member;
  }
  /**
   * @return string
   */
  public function getMember()
  {
    return $this->member;
  }
  /**
   * @param string
   */
  public function setRole($role)
  {
    $this->role = $role;
  }
  /**
   * @return string
   */
  public function getRole()
  {
    return $this->role;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleIamV1BindingDelta::class, 'Google_Service_Iam_GoogleIamV1BindingDelta');
