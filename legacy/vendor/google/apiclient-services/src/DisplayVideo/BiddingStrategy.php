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

namespace Google\Service\DisplayVideo;

class BiddingStrategy extends \Google\Model
{
  protected $fixedBidType = FixedBidStrategy::class;
  protected $fixedBidDataType = '';
  protected $maximizeSpendAutoBidType = MaximizeSpendBidStrategy::class;
  protected $maximizeSpendAutoBidDataType = '';
  protected $performanceGoalAutoBidType = PerformanceGoalBidStrategy::class;
  protected $performanceGoalAutoBidDataType = '';
  protected $youtubeAndPartnersBidType = YoutubeAndPartnersBiddingStrategy::class;
  protected $youtubeAndPartnersBidDataType = '';

  /**
   * @param FixedBidStrategy
   */
  public function setFixedBid(FixedBidStrategy $fixedBid)
  {
    $this->fixedBid = $fixedBid;
  }
  /**
   * @return FixedBidStrategy
   */
  public function getFixedBid()
  {
    return $this->fixedBid;
  }
  /**
   * @param MaximizeSpendBidStrategy
   */
  public function setMaximizeSpendAutoBid(MaximizeSpendBidStrategy $maximizeSpendAutoBid)
  {
    $this->maximizeSpendAutoBid = $maximizeSpendAutoBid;
  }
  /**
   * @return MaximizeSpendBidStrategy
   */
  public function getMaximizeSpendAutoBid()
  {
    return $this->maximizeSpendAutoBid;
  }
  /**
   * @param PerformanceGoalBidStrategy
   */
  public function setPerformanceGoalAutoBid(PerformanceGoalBidStrategy $performanceGoalAutoBid)
  {
    $this->performanceGoalAutoBid = $performanceGoalAutoBid;
  }
  /**
   * @return PerformanceGoalBidStrategy
   */
  public function getPerformanceGoalAutoBid()
  {
    return $this->performanceGoalAutoBid;
  }
  /**
   * @param YoutubeAndPartnersBiddingStrategy
   */
  public function setYoutubeAndPartnersBid(YoutubeAndPartnersBiddingStrategy $youtubeAndPartnersBid)
  {
    $this->youtubeAndPartnersBid = $youtubeAndPartnersBid;
  }
  /**
   * @return YoutubeAndPartnersBiddingStrategy
   */
  public function getYoutubeAndPartnersBid()
  {
    return $this->youtubeAndPartnersBid;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(BiddingStrategy::class, 'Google_Service_DisplayVideo_BiddingStrategy');
