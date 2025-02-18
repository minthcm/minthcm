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

namespace Google\Service\ServiceUsage;

class GoogleApiServiceusageV1beta1GetServiceIdentityResponse extends \Google\Model
{
  protected $identityType = GoogleApiServiceusageV1beta1ServiceIdentity::class;
  protected $identityDataType = '';
  /**
   * @var string
   */
  public $state;

  /**
   * @param GoogleApiServiceusageV1beta1ServiceIdentity
   */
  public function setIdentity(GoogleApiServiceusageV1beta1ServiceIdentity $identity)
  {
    $this->identity = $identity;
  }
  /**
   * @return GoogleApiServiceusageV1beta1ServiceIdentity
   */
  public function getIdentity()
  {
    return $this->identity;
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
class_alias(GoogleApiServiceusageV1beta1GetServiceIdentityResponse::class, 'Google_Service_ServiceUsage_GoogleApiServiceusageV1beta1GetServiceIdentityResponse');
