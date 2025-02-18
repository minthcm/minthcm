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

namespace Google\Service\Dialogflow;

class GoogleCloudDialogflowCxV3beta1TestConfig extends \Google\Collection
{
  protected $collection_key = 'trackingParameters';
  /**
   * @var string
   */
  public $flow;
  /**
   * @var string
   */
  public $page;
  /**
   * @var string[]
   */
  public $trackingParameters;

  /**
   * @param string
   */
  public function setFlow($flow)
  {
    $this->flow = $flow;
  }
  /**
   * @return string
   */
  public function getFlow()
  {
    return $this->flow;
  }
  /**
   * @param string
   */
  public function setPage($page)
  {
    $this->page = $page;
  }
  /**
   * @return string
   */
  public function getPage()
  {
    return $this->page;
  }
  /**
   * @param string[]
   */
  public function setTrackingParameters($trackingParameters)
  {
    $this->trackingParameters = $trackingParameters;
  }
  /**
   * @return string[]
   */
  public function getTrackingParameters()
  {
    return $this->trackingParameters;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDialogflowCxV3beta1TestConfig::class, 'Google_Service_Dialogflow_GoogleCloudDialogflowCxV3beta1TestConfig');
