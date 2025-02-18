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

namespace Google\Service\VMwareEngine;

class DnsBindPermission extends \Google\Collection
{
  protected $collection_key = 'principals';
  /**
   * @var string
   */
  public $name;
  protected $principalsType = Principal::class;
  protected $principalsDataType = 'array';

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
   * @param Principal[]
   */
  public function setPrincipals($principals)
  {
    $this->principals = $principals;
  }
  /**
   * @return Principal[]
   */
  public function getPrincipals()
  {
    return $this->principals;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DnsBindPermission::class, 'Google_Service_VMwareEngine_DnsBindPermission');
