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

class RouterNatRuleAction extends \Google\Collection
{
  protected $collection_key = 'sourceNatDrainRanges';
  /**
   * @var string[]
   */
  public $sourceNatActiveIps;
  /**
   * @var string[]
   */
  public $sourceNatActiveRanges;
  /**
   * @var string[]
   */
  public $sourceNatDrainIps;
  /**
   * @var string[]
   */
  public $sourceNatDrainRanges;

  /**
   * @param string[]
   */
  public function setSourceNatActiveIps($sourceNatActiveIps)
  {
    $this->sourceNatActiveIps = $sourceNatActiveIps;
  }
  /**
   * @return string[]
   */
  public function getSourceNatActiveIps()
  {
    return $this->sourceNatActiveIps;
  }
  /**
   * @param string[]
   */
  public function setSourceNatActiveRanges($sourceNatActiveRanges)
  {
    $this->sourceNatActiveRanges = $sourceNatActiveRanges;
  }
  /**
   * @return string[]
   */
  public function getSourceNatActiveRanges()
  {
    return $this->sourceNatActiveRanges;
  }
  /**
   * @param string[]
   */
  public function setSourceNatDrainIps($sourceNatDrainIps)
  {
    $this->sourceNatDrainIps = $sourceNatDrainIps;
  }
  /**
   * @return string[]
   */
  public function getSourceNatDrainIps()
  {
    return $this->sourceNatDrainIps;
  }
  /**
   * @param string[]
   */
  public function setSourceNatDrainRanges($sourceNatDrainRanges)
  {
    $this->sourceNatDrainRanges = $sourceNatDrainRanges;
  }
  /**
   * @return string[]
   */
  public function getSourceNatDrainRanges()
  {
    return $this->sourceNatDrainRanges;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(RouterNatRuleAction::class, 'Google_Service_Compute_RouterNatRuleAction');
