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

class SpokeSummary extends \Google\Collection
{
  protected $collection_key = 'spokeTypeCounts';
  protected $spokeStateCountsType = SpokeStateCount::class;
  protected $spokeStateCountsDataType = 'array';
  protected $spokeStateReasonCountsType = SpokeStateReasonCount::class;
  protected $spokeStateReasonCountsDataType = 'array';
  protected $spokeTypeCountsType = SpokeTypeCount::class;
  protected $spokeTypeCountsDataType = 'array';

  /**
   * @param SpokeStateCount[]
   */
  public function setSpokeStateCounts($spokeStateCounts)
  {
    $this->spokeStateCounts = $spokeStateCounts;
  }
  /**
   * @return SpokeStateCount[]
   */
  public function getSpokeStateCounts()
  {
    return $this->spokeStateCounts;
  }
  /**
   * @param SpokeStateReasonCount[]
   */
  public function setSpokeStateReasonCounts($spokeStateReasonCounts)
  {
    $this->spokeStateReasonCounts = $spokeStateReasonCounts;
  }
  /**
   * @return SpokeStateReasonCount[]
   */
  public function getSpokeStateReasonCounts()
  {
    return $this->spokeStateReasonCounts;
  }
  /**
   * @param SpokeTypeCount[]
   */
  public function setSpokeTypeCounts($spokeTypeCounts)
  {
    $this->spokeTypeCounts = $spokeTypeCounts;
  }
  /**
   * @return SpokeTypeCount[]
   */
  public function getSpokeTypeCounts()
  {
    return $this->spokeTypeCounts;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SpokeSummary::class, 'Google_Service_Networkconnectivity_SpokeSummary');
