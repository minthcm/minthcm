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

namespace Google\Service\Compute;

class Reference extends \Google\Model
{
  /**
   * @var string
   */
  public $kind;
  /**
   * @var string
   */
  public $referenceType;
  /**
   * @var string
   */
  public $referrer;
  /**
   * @var string
   */
  public $target;

  /**
   * @param string
   */
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  /**
   * @return string
   */
  public function getKind()
  {
    return $this->kind;
  }
  /**
   * @param string
   */
  public function setReferenceType($referenceType)
  {
    $this->referenceType = $referenceType;
  }
  /**
   * @return string
   */
  public function getReferenceType()
  {
    return $this->referenceType;
  }
  /**
   * @param string
   */
  public function setReferrer($referrer)
  {
    $this->referrer = $referrer;
  }
  /**
   * @return string
   */
  public function getReferrer()
  {
    return $this->referrer;
  }
  /**
   * @param string
   */
  public function setTarget($target)
  {
    $this->target = $target;
  }
  /**
   * @return string
   */
  public function getTarget()
  {
    return $this->target;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Reference::class, 'Google_Service_Compute_Reference');
