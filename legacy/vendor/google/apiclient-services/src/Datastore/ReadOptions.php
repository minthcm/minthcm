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

class ReadOptions extends \Google\Model
{
  protected $newTransactionType = TransactionOptions::class;
  protected $newTransactionDataType = '';
  /**
   * @var string
   */
  public $readConsistency;
  /**
   * @var string
   */
  public $readTime;
  /**
   * @var string
   */
  public $transaction;

  /**
   * @param TransactionOptions
   */
  public function setNewTransaction(TransactionOptions $newTransaction)
  {
    $this->newTransaction = $newTransaction;
  }
  /**
   * @return TransactionOptions
   */
  public function getNewTransaction()
  {
    return $this->newTransaction;
  }
  /**
   * @param string
   */
  public function setReadConsistency($readConsistency)
  {
    $this->readConsistency = $readConsistency;
  }
  /**
   * @return string
   */
  public function getReadConsistency()
  {
    return $this->readConsistency;
  }
  /**
   * @param string
   */
  public function setReadTime($readTime)
  {
    $this->readTime = $readTime;
  }
  /**
   * @return string
   */
  public function getReadTime()
  {
    return $this->readTime;
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
class_alias(ReadOptions::class, 'Google_Service_Datastore_ReadOptions');
