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

namespace Google\Service\RealTimeBidding;

class PublisherConnection extends \Google\Model
{
  /**
   * @var string
   */
  public $biddingState;
  /**
   * @var string
   */
  public $createTime;
  /**
   * @var string
   */
  public $displayName;
  /**
   * @var string
   */
  public $name;
  /**
   * @var string
   */
  public $publisherPlatform;

  /**
   * @param string
   */
  public function setBiddingState($biddingState)
  {
    $this->biddingState = $biddingState;
  }
  /**
   * @return string
   */
  public function getBiddingState()
  {
    return $this->biddingState;
  }
  /**
   * @param string
   */
  public function setCreateTime($createTime)
  {
    $this->createTime = $createTime;
  }
  /**
   * @return string
   */
  public function getCreateTime()
  {
    return $this->createTime;
  }
  /**
   * @param string
   */
  public function setDisplayName($displayName)
  {
    $this->displayName = $displayName;
  }
  /**
   * @return string
   */
  public function getDisplayName()
  {
    return $this->displayName;
  }
  /**
   * @param string
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
  /**
   * @param string
   */
  public function setPublisherPlatform($publisherPlatform)
  {
    $this->publisherPlatform = $publisherPlatform;
  }
  /**
   * @return string
   */
  public function getPublisherPlatform()
  {
    return $this->publisherPlatform;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(PublisherConnection::class, 'Google_Service_RealTimeBidding_PublisherConnection');
