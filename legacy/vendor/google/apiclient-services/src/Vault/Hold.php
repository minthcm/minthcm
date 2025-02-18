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

namespace Google\Service\Vault;

class Hold extends \Google\Collection
{
  protected $collection_key = 'accounts';
  protected $accountsType = HeldAccount::class;
  protected $accountsDataType = 'array';
  /**
   * @var string
   */
  public $corpus;
  /**
   * @var string
   */
  public $holdId;
  /**
   * @var string
   */
  public $name;
  protected $orgUnitType = HeldOrgUnit::class;
  protected $orgUnitDataType = '';
  protected $queryType = CorpusQuery::class;
  protected $queryDataType = '';
  /**
   * @var string
   */
  public $updateTime;

  /**
   * @param HeldAccount[]
   */
  public function setAccounts($accounts)
  {
    $this->accounts = $accounts;
  }
  /**
   * @return HeldAccount[]
   */
  public function getAccounts()
  {
    return $this->accounts;
  }
  /**
   * @param string
   */
  public function setCorpus($corpus)
  {
    $this->corpus = $corpus;
  }
  /**
   * @return string
   */
  public function getCorpus()
  {
    return $this->corpus;
  }
  /**
   * @param string
   */
  public function setHoldId($holdId)
  {
    $this->holdId = $holdId;
  }
  /**
   * @return string
   */
  public function getHoldId()
  {
    return $this->holdId;
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
   * @param HeldOrgUnit
   */
  public function setOrgUnit(HeldOrgUnit $orgUnit)
  {
    $this->orgUnit = $orgUnit;
  }
  /**
   * @return HeldOrgUnit
   */
  public function getOrgUnit()
  {
    return $this->orgUnit;
  }
  /**
   * @param CorpusQuery
   */
  public function setQuery(CorpusQuery $query)
  {
    $this->query = $query;
  }
  /**
   * @return CorpusQuery
   */
  public function getQuery()
  {
    return $this->query;
  }
  /**
   * @param string
   */
  public function setUpdateTime($updateTime)
  {
    $this->updateTime = $updateTime;
  }
  /**
   * @return string
   */
  public function getUpdateTime()
  {
    return $this->updateTime;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Hold::class, 'Google_Service_Vault_Hold');
