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

namespace Google\Service\WorkflowExecutions;

class TriggerPubsubExecutionRequest extends \Google\Model
{
  protected $internal_gapi_mappings = [
        "gCPCloudEventsMode" => "GCPCloudEventsMode",
  ];
  /**
   * @var string
   */
  public $gCPCloudEventsMode;
  /**
   * @var int
   */
  public $deliveryAttempt;
  protected $messageType = PubsubMessage::class;
  protected $messageDataType = '';
  /**
   * @var string
   */
  public $subscription;

  /**
   * @param string
   */
  public function setGCPCloudEventsMode($gCPCloudEventsMode)
  {
    $this->gCPCloudEventsMode = $gCPCloudEventsMode;
  }
  /**
   * @return string
   */
  public function getGCPCloudEventsMode()
  {
    return $this->gCPCloudEventsMode;
  }
  /**
   * @param int
   */
  public function setDeliveryAttempt($deliveryAttempt)
  {
    $this->deliveryAttempt = $deliveryAttempt;
  }
  /**
   * @return int
   */
  public function getDeliveryAttempt()
  {
    return $this->deliveryAttempt;
  }
  /**
   * @param PubsubMessage
   */
  public function setMessage(PubsubMessage $message)
  {
    $this->message = $message;
  }
  /**
   * @return PubsubMessage
   */
  public function getMessage()
  {
    return $this->message;
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
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(TriggerPubsubExecutionRequest::class, 'Google_Service_WorkflowExecutions_TriggerPubsubExecutionRequest');
