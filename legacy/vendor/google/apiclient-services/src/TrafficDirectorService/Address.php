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

class Address extends \Google\Model
{
  protected $envoyInternalAddressType = EnvoyInternalAddress::class;
  protected $envoyInternalAddressDataType = '';
  protected $pipeType = Pipe::class;
  protected $pipeDataType = '';
  protected $socketAddressType = SocketAddress::class;
  protected $socketAddressDataType = '';

  /**
   * @param EnvoyInternalAddress
   */
  public function setEnvoyInternalAddress(EnvoyInternalAddress $envoyInternalAddress)
  {
    $this->envoyInternalAddress = $envoyInternalAddress;
  }
  /**
   * @return EnvoyInternalAddress
   */
  public function getEnvoyInternalAddress()
  {
    return $this->envoyInternalAddress;
  }
  /**
   * @param Pipe
   */
  public function setPipe(Pipe $pipe)
  {
    $this->pipe = $pipe;
  }
  /**
   * @return Pipe
   */
  public function getPipe()
  {
    return $this->pipe;
  }
  /**
   * @param SocketAddress
   */
  public function setSocketAddress(SocketAddress $socketAddress)
  {
    $this->socketAddress = $socketAddress;
  }
  /**
   * @return SocketAddress
   */
  public function getSocketAddress()
  {
    return $this->socketAddress;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Address::class, 'Google_Service_TrafficDirectorService_Address');
