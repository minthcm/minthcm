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

namespace Google\Service\CloudRetail;

class GoogleCloudRetailV2SetDefaultBranchRequest extends \Google\Model
{
  /**
   * @var string
   */
  public $branchId;
  /**
   * @var bool
   */
  public $force;
  /**
   * @var string
   */
  public $note;

  /**
   * @param string
   */
  public function setBranchId($branchId)
  {
    $this->branchId = $branchId;
  }
  /**
   * @return string
   */
  public function getBranchId()
  {
    return $this->branchId;
  }
  /**
   * @param bool
   */
  public function setForce($force)
  {
    $this->force = $force;
  }
  /**
   * @return bool
   */
  public function getForce()
  {
    return $this->force;
  }
  /**
   * @param string
   */
  public function setNote($note)
  {
    $this->note = $note;
  }
  /**
   * @return string
   */
  public function getNote()
  {
    return $this->note;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudRetailV2SetDefaultBranchRequest::class, 'Google_Service_CloudRetail_GoogleCloudRetailV2SetDefaultBranchRequest');
