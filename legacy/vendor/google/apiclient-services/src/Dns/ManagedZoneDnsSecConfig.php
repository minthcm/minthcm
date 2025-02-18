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

class ManagedZoneDnsSecConfig extends \Google\Collection
{
  protected $collection_key = 'defaultKeySpecs';
  protected $defaultKeySpecsType = DnsKeySpec::class;
  protected $defaultKeySpecsDataType = 'array';
  /**
   * @var string
   */
  public $kind;
  /**
   * @var string
   */
  public $nonExistence;
  /**
   * @var string
   */
  public $state;

  /**
   * @param DnsKeySpec[]
   */
  public function setDefaultKeySpecs($defaultKeySpecs)
  {
    $this->defaultKeySpecs = $defaultKeySpecs;
  }
  /**
   * @return DnsKeySpec[]
   */
  public function getDefaultKeySpecs()
  {
    return $this->defaultKeySpecs;
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
  public function setNonExistence($nonExistence)
  {
    $this->nonExistence = $nonExistence;
  }
  /**
   * @return string
   */
  public function getNonExistence()
  {
    return $this->nonExistence;
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
class_alias(ManagedZoneDnsSecConfig::class, 'Google_Service_Dns_ManagedZoneDnsSecConfig');
