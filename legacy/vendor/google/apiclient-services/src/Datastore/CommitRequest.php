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

namespace Google\Service\Datastore;

class CommitRequest extends \Google\Collection
{
  protected $collection_key = 'mutations';
  /**
   * @var string
   */
  public $databaseId;
  /**
   * @var string
   */
  public $mode;
  protected $mutationsType = Mutation::class;
  protected $mutationsDataType = 'array';
  protected $singleUseTransactionType = TransactionOptions::class;
  protected $singleUseTransactionDataType = '';
  /**
   * @var string
   */
  public $transaction;

  /**
   * @param string
   */
  public function setDatabaseId($databaseId)
  {
    $this->databaseId = $databaseId;
  }
  /**
   * @return string
   */
  public function getDatabaseId()
  {
    return $this->databaseId;
  }
  /**
   * @param string
   */
  public function setMode($mode)
  {
    $this->mode = $mode;
  }
  /**
   * @return string
   */
  public function getMode()
  {
    return $this->mode;
  }
  /**
   * @param Mutation[]
   */
  public function setMutations($mutations)
  {
    $this->mutations = $mutations;
  }
  /**
   * @return Mutation[]
   */
  public function getMutations()
  {
    return $this->mutations;
  }
  /**
   * @param TransactionOptions
   */
  public function setSingleUseTransaction(TransactionOptions $singleUseTransaction)
  {
    $this->singleUseTransaction = $singleUseTransaction;
  }
  /**
   * @return TransactionOptions
   */
  public function getSingleUseTransaction()
  {
    return $this->singleUseTransaction;
  }
  /**
   * @param string
   */
  public function setTransaction($transaction)
  {
    $this->transaction = $transaction;
  }
  /**
   * @return string
   */
  public function getTransaction()
  {
    return $this->transaction;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CommitRequest::class, 'Google_Service_Datastore_CommitRequest');
