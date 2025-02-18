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

namespace Google\Service\Testing;

class Shard extends \Google\Model
{
  /**
   * @var string
   */
  public $estimatedShardDuration;
  /**
   * @var int
   */
  public $numShards;
  /**
   * @var int
   */
  public $shardIndex;
  protected $testTargetsForShardType = TestTargetsForShard::class;
  protected $testTargetsForShardDataType = '';

  /**
   * @param string
   */
  public function setEstimatedShardDuration($estimatedShardDuration)
  {
    $this->estimatedShardDuration = $estimatedShardDuration;
  }
  /**
   * @return string
   */
  public function getEstimatedShardDuration()
  {
    return $this->estimatedShardDuration;
  }
  /**
   * @param int
   */
  public function setNumShards($numShards)
  {
    $this->numShards = $numShards;
  }
  /**
   * @return int
   */
  public function getNumShards()
  {
    return $this->numShards;
  }
  /**
   * @param int
   */
  public function setShardIndex($shardIndex)
  {
    $this->shardIndex = $shardIndex;
  }
  /**
   * @return int
   */
  public function getShardIndex()
  {
    return $this->shardIndex;
  }
  /**
   * @param TestTargetsForShard
   */
  public function setTestTargetsForShard(TestTargetsForShard $testTargetsForShard)
  {
    $this->testTargetsForShard = $testTargetsForShard;
  }
  /**
   * @return TestTargetsForShard
   */
  public function getTestTargetsForShard()
  {
    return $this->testTargetsForShard;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Shard::class, 'Google_Service_Testing_Shard');
