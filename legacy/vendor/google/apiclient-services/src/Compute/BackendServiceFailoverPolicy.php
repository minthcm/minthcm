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

class BackendServiceFailoverPolicy extends \Google\Model
{
  /**
   * @var bool
   */
  public $disableConnectionDrainOnFailover;
  /**
   * @var bool
   */
  public $dropTrafficIfUnhealthy;
  /**
   * @var float
   */
  public $failoverRatio;

  /**
   * @param bool
   */
  public function setDisableConnectionDrainOnFailover($disableConnectionDrainOnFailover)
  {
    $this->disableConnectionDrainOnFailover = $disableConnectionDrainOnFailover;
  }
  /**
   * @return bool
   */
  public function getDisableConnectionDrainOnFailover()
  {
    return $this->disableConnectionDrainOnFailover;
  }
  /**
   * @param bool
   */
  public function setDropTrafficIfUnhealthy($dropTrafficIfUnhealthy)
  {
    $this->dropTrafficIfUnhealthy = $dropTrafficIfUnhealthy;
  }
  /**
   * @return bool
   */
  public function getDropTrafficIfUnhealthy()
  {
    return $this->dropTrafficIfUnhealthy;
  }
  /**
   * @param float
   */
  public function setFailoverRatio($failoverRatio)
  {
    $this->failoverRatio = $failoverRatio;
  }
  /**
   * @return float
   */
  public function getFailoverRatio()
  {
    return $this->failoverRatio;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(BackendServiceFailoverPolicy::class, 'Google_Service_Compute_BackendServiceFailoverPolicy');
