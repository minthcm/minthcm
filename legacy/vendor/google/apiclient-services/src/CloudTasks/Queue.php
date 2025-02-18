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

namespace Google\Service\CloudTasks;

class Queue extends \Google\Model
{
  protected $appEngineRoutingOverrideType = AppEngineRouting::class;
  protected $appEngineRoutingOverrideDataType = '';
  protected $httpTargetType = HttpTarget::class;
  protected $httpTargetDataType = '';
  /**
   * @var string
   */
  public $name;
  /**
   * @var string
   */
  public $purgeTime;
  protected $rateLimitsType = RateLimits::class;
  protected $rateLimitsDataType = '';
  protected $retryConfigType = RetryConfig::class;
  protected $retryConfigDataType = '';
  protected $stackdriverLoggingConfigType = StackdriverLoggingConfig::class;
  protected $stackdriverLoggingConfigDataType = '';
  /**
   * @var string
   */
  public $state;

  /**
   * @param AppEngineRouting
   */
  public function setAppEngineRoutingOverride(AppEngineRouting $appEngineRoutingOverride)
  {
    $this->appEngineRoutingOverride = $appEngineRoutingOverride;
  }
  /**
   * @return AppEngineRouting
   */
  public function getAppEngineRoutingOverride()
  {
    return $this->appEngineRoutingOverride;
  }
  /**
   * @param HttpTarget
   */
  public function setHttpTarget(HttpTarget $httpTarget)
  {
    $this->httpTarget = $httpTarget;
  }
  /**
   * @return HttpTarget
   */
  public function getHttpTarget()
  {
    return $this->httpTarget;
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
  public function setPurgeTime($purgeTime)
  {
    $this->purgeTime = $purgeTime;
  }
  /**
   * @return string
   */
  public function getPurgeTime()
  {
    return $this->purgeTime;
  }
  /**
   * @param RateLimits
   */
  public function setRateLimits(RateLimits $rateLimits)
  {
    $this->rateLimits = $rateLimits;
  }
  /**
   * @return RateLimits
   */
  public function getRateLimits()
  {
    return $this->rateLimits;
  }
  /**
   * @param RetryConfig
   */
  public function setRetryConfig(RetryConfig $retryConfig)
  {
    $this->retryConfig = $retryConfig;
  }
  /**
   * @return RetryConfig
   */
  public function getRetryConfig()
  {
    return $this->retryConfig;
  }
  /**
   * @param StackdriverLoggingConfig
   */
  public function setStackdriverLoggingConfig(StackdriverLoggingConfig $stackdriverLoggingConfig)
  {
    $this->stackdriverLoggingConfig = $stackdriverLoggingConfig;
  }
  /**
   * @return StackdriverLoggingConfig
   */
  public function getStackdriverLoggingConfig()
  {
    return $this->stackdriverLoggingConfig;
  }
  /**
   * @param string
   */
  public function setState($state)
  {
    $this->state = $state;
  }
  /**
   * @return string
   */
  public function getState()
  {
    return $this->state;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Queue::class, 'Google_Service_CloudTasks_Queue');
