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

namespace Google\Service\TrafficDirectorService;

class EnvoyInternalAddress extends \Google\Model
{
  /**
   * @var string
   */
  public $endpointId;
  /**
   * @var string
   */
  public $serverListenerName;

  /**
   * @param string
   */
  public function setEndpointId($endpointId)
  {
    $this->endpointId = $endpointId;
  }
  /**
   * @return string
   */
  public function getEndpointId()
  {
    return $this->endpointId;
  }
  /**
   * @param string
   */
  public function setServerListenerName($serverListenerName)
  {
    $this->serverListenerName = $serverListenerName;
  }
  /**
   * @return string
   */
  public function getServerListenerName()
  {
    return $this->serverListenerName;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(EnvoyInternalAddress::class, 'Google_Service_TrafficDirectorService_EnvoyInternalAddress');
