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

namespace Google\Service\Networkconnectivity;

class StateReason extends \Google\Model
{
  /**
   * @var string
   */
  public $code;
  /**
   * @var string
   */
  public $message;
  /**
   * @var string
   */
  public $userDetails;

  /**
   * @param string
   */
  public function setCode($code)
  {
    $this->code = $code;
  }
  /**
   * @return string
   */
  public function getCode()
  {
    return $this->code;
  }
  /**
   * @param string
   */
  public function setMessage($message)
  {
    $this->message = $message;
  }
  /**
   * @return string
   */
  public function getMessage()
  {
    return $this->message;
  }
  /**
   * @param string
   */
  public function setUserDetails($userDetails)
  {
    $this->userDetails = $userDetails;
  }
  /**
   * @return string
   */
  public function getUserDetails()
  {
    return $this->userDetails;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(StateReason::class, 'Google_Service_Networkconnectivity_StateReason');
