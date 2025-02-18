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

namespace Google\Service\SecurityCommandCenter;

class Database extends \Google\Collection
{
  protected $collection_key = 'grantees';
  /**
   * @var string
   */
  public $displayName;
  /**
   * @var string[]
   */
  public $grantees;
  /**
   * @var string
   */
  public $name;
  /**
   * @var string
   */
  public $query;
  /**
   * @var string
   */
  public $userName;
  /**
   * @var string
   */
  public $version;

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
   * @param string[]
   */
  public function setGrantees($grantees)
  {
    $this->grantees = $grantees;
  }
  /**
   * @return string[]
   */
  public function getGrantees()
  {
    return $this->grantees;
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
  public function setQuery($query)
  {
    $this->query = $query;
  }
  /**
   * @return string
   */
  public function getQuery()
  {
    return $this->query;
  }
  /**
   * @param string
   */
  public function setUserName($userName)
  {
    $this->userName = $userName;
  }
  /**
   * @return string
   */
  public function getUserName()
  {
    return $this->userName;
  }
  /**
   * @param string
   */
  public function setVersion($version)
  {
    $this->version = $version;
  }
  /**
   * @return string
   */
  public function getVersion()
  {
    return $this->version;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Database::class, 'Google_Service_SecurityCommandCenter_Database');
