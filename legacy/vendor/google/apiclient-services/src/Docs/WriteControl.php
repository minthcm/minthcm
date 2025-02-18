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

namespace Google\Service\Docs;

class WriteControl extends \Google\Model
{
  /**
   * @var string
   */
  public $requiredRevisionId;
  /**
   * @var string
   */
  public $targetRevisionId;

  /**
   * @param string
   */
  public function setRequiredRevisionId($requiredRevisionId)
  {
    $this->requiredRevisionId = $requiredRevisionId;
  }
  /**
   * @return string
   */
  public function getRequiredRevisionId()
  {
    return $this->requiredRevisionId;
  }
  /**
   * @param string
   */
  public function setTargetRevisionId($targetRevisionId)
  {
    $this->targetRevisionId = $targetRevisionId;
  }
  /**
   * @return string
   */
  public function getTargetRevisionId()
  {
    return $this->targetRevisionId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(WriteControl::class, 'Google_Service_Docs_WriteControl');
