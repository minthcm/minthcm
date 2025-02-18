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

namespace Google\Service\Dataflow;

class PubsubLocation extends \Google\Model
{
  /**
   * @var bool
   */
  public $dropLateData;
  /**
   * @var bool
   */
  public $dynamicDestinations;
  /**
   * @var string
   */
  public $idLabel;
  /**
   * @var string
   */
  public $subscription;
  /**
   * @var string
   */
  public $timestampLabel;
  /**
   * @var string
   */
  public $topic;
  /**
   * @var string
   */
  public $trackingSubscription;
  /**
   * @var bool
   */
  public $withAttributes;

  /**
   * @param bool
   */
  public function setDropLateData($dropLateData)
  {
    $this->dropLateData = $dropLateData;
  }
  /**
   * @return bool
   */
  public function getDropLateData()
  {
    return $this->dropLateData;
  }
  /**
   * @param bool
   */
  public function setDynamicDestinations($dynamicDestinations)
  {
    $this->dynamicDestinations = $dynamicDestinations;
  }
  /**
   * @return bool
   */
  public function getDynamicDestinations()
  {
    return $this->dynamicDestinations;
  }
  /**
   * @param string
   */
  public function setIdLabel($idLabel)
  {
    $this->idLabel = $idLabel;
  }
  /**
   * @return string
   */
  public function getIdLabel()
  {
    return $this->idLabel;
  }
  /**
   * @param string
   */
  public function setSubscription($subscription)
  {
    $this->subscription = $subscription;
  }
  /**
   * @return string
   */
  public function getSubscription()
  {
    return $this->subscription;
  }
  /**
   * @param string
   */
  public function setTimestampLabel($timestampLabel)
  {
    $this->timestampLabel = $timestampLabel;
  }
  /**
   * @return string
   */
  public function getTimestampLabel()
  {
    return $this->timestampLabel;
  }
  /**
   * @param string
   */
  public function setTopic($topic)
  {
    $this->topic = $topic;
  }
  /**
   * @return string
   */
  public function getTopic()
  {
    return $this->topic;
  }
  /**
   * @param string
   */
  public function setTrackingSubscription($trackingSubscription)
  {
    $this->trackingSubscription = $trackingSubscription;
  }
  /**
   * @return string
   */
  public function getTrackingSubscription()
  {
    return $this->trackingSubscription;
  }
  /**
   * @param bool
   */
  public function setWithAttributes($withAttributes)
  {
    $this->withAttributes = $withAttributes;
  }
  /**
   * @return bool
   */
  public function getWithAttributes()
  {
    return $this->withAttributes;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(PubsubLocation::class, 'Google_Service_Dataflow_PubsubLocation');
