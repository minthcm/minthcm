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

class PostureDetail extends \Google\Collection
{
  protected $collection_key = 'advice';
  protected $adviceType = UserFacingMessage::class;
  protected $adviceDataType = 'array';
  /**
   * @var string
   */
  public $securityRisk;

  /**
   * @param UserFacingMessage[]
   */
  public function setAdvice($advice)
  {
    $this->advice = $advice;
  }
  /**
   * @return UserFacingMessage[]
   */
  public function getAdvice()
  {
    return $this->advice;
  }
  /**
   * @param string
   */
  public function setSecurityRisk($securityRisk)
  {
    $this->securityRisk = $securityRisk;
  }
  /**
   * @return string
   */
  public function getSecurityRisk()
  {
    return $this->securityRisk;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(PostureDetail::class, 'Google_Service_AndroidManagement_PostureDetail');
