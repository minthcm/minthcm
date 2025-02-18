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

class MailCountResult extends \Google\Collection
{
  protected $collection_key = 'nonQueryableAccounts';
  protected $accountCountErrorsType = AccountCountError::class;
  protected $accountCountErrorsDataType = 'array';
  protected $accountCountsType = AccountCount::class;
  protected $accountCountsDataType = 'array';
  /**
   * @var string
   */
  public $matchingAccountsCount;
  /**
   * @var string[]
   */
  public $nonQueryableAccounts;
  /**
   * @var string
   */
  public $queriedAccountsCount;

  /**
   * @param AccountCountError[]
   */
  public function setAccountCountErrors($accountCountErrors)
  {
    $this->accountCountErrors = $accountCountErrors;
  }
  /**
   * @return AccountCountError[]
   */
  public function getAccountCountErrors()
  {
    return $this->accountCountErrors;
  }
  /**
   * @param AccountCount[]
   */
  public function setAccountCounts($accountCounts)
  {
    $this->accountCounts = $accountCounts;
  }
  /**
   * @return AccountCount[]
   */
  public function getAccountCounts()
  {
    return $this->accountCounts;
  }
  /**
   * @param string
   */
  public function setMatchingAccountsCount($matchingAccountsCount)
  {
    $this->matchingAccountsCount = $matchingAccountsCount;
  }
  /**
   * @return string
   */
  public function getMatchingAccountsCount()
  {
    return $this->matchingAccountsCount;
  }
  /**
   * @param string[]
   */
  public function setNonQueryableAccounts($nonQueryableAccounts)
  {
    $this->nonQueryableAccounts = $nonQueryableAccounts;
  }
  /**
   * @return string[]
   */
  public function getNonQueryableAccounts()
  {
    return $this->nonQueryableAccounts;
  }
  /**
   * @param string
   */
  public function setQueriedAccountsCount($queriedAccountsCount)
  {
    $this->queriedAccountsCount = $queriedAccountsCount;
  }
  /**
   * @return string
   */
  public function getQueriedAccountsCount()
  {
    return $this->queriedAccountsCount;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(MailCountResult::class, 'Google_Service_Vault_MailCountResult');
