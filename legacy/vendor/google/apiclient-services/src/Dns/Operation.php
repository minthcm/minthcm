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

namespace Google\Service\Dns;

class Operation extends \Google\Model
{
  protected $dnsKeyContextType = OperationDnsKeyContext::class;
  protected $dnsKeyContextDataType = '';
  /**
   * @var string
   */
  public $id;
  /**
   * @var string
   */
  public $kind;
  /**
   * @var string
   */
  public $startTime;
  /**
   * @var string
   */
  public $status;
  /**
   * @var string
   */
  public $type;
  /**
   * @var string
   */
  public $user;
  protected $zoneContextType = OperationManagedZoneContext::class;
  protected $zoneContextDataType = '';

  /**
   * @param OperationDnsKeyContext
   */
  public function setDnsKeyContext(OperationDnsKeyContext $dnsKeyContext)
  {
    $this->dnsKeyContext = $dnsKeyContext;
  }
  /**
   * @return OperationDnsKeyContext
   */
  public function getDnsKeyContext()
  {
    return $this->dnsKeyContext;
  }
  /**
   * @param string
   */
  public function setId($id)
  {
    $this->id = $id;
  }
  /**
   * @return string
   */
  public function getId()
  {
    return $this->id;
  }
  /**
   * @param string
   */
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  /**
   * @return string
   */
  public function getKind()
  {
    return $this->kind;
  }
  /**
   * @param string
   */
  public function setStartTime($startTime)
  {
    $this->startTime = $startTime;
  }
  /**
   * @return string
   */
  public function getStartTime()
  {
    return $this->startTime;
  }
  /**
   * @param string
   */
  public function setStatus($status)
  {
    $this->status = $status;
  }
  /**
   * @return string
   */
  public function getStatus()
  {
    return $this->status;
  }
  /**
   * @param string
   */
  public function setType($type)
  {
    $this->type = $type;
  }
  /**
   * @return string
   */
  public function getType()
  {
    return $this->type;
  }
  /**
   * @param string
   */
  public function setUser($user)
  {
    $this->user = $user;
  }
  /**
   * @return string
   */
  public function getUser()
  {
    return $this->user;
  }
  /**
   * @param OperationManagedZoneContext
   */
  public function setZoneContext(OperationManagedZoneContext $zoneContext)
  {
    $this->zoneContext = $zoneContext;
  }
  /**
   * @return OperationManagedZoneContext
   */
  public function getZoneContext()
  {
    return $this->zoneContext;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Operation::class, 'Google_Service_Dns_Operation');
