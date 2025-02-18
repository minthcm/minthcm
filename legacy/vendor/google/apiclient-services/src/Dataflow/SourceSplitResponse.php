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

namespace Google\Service\Dataflow;

class SourceSplitResponse extends \Google\Collection
{
  protected $collection_key = 'shards';
  protected $bundlesType = DerivedSource::class;
  protected $bundlesDataType = 'array';
  /**
   * @var string
   */
  public $outcome;
  protected $shardsType = SourceSplitShard::class;
  protected $shardsDataType = 'array';

  /**
   * @param DerivedSource[]
   */
  public function setBundles($bundles)
  {
    $this->bundles = $bundles;
  }
  /**
   * @return DerivedSource[]
   */
  public function getBundles()
  {
    return $this->bundles;
  }
  /**
   * @param string
   */
  public function setOutcome($outcome)
  {
    $this->outcome = $outcome;
  }
  /**
   * @return string
   */
  public function getOutcome()
  {
    return $this->outcome;
  }
  /**
   * @param SourceSplitShard[]
   */
  public function setShards($shards)
  {
    $this->shards = $shards;
  }
  /**
   * @return SourceSplitShard[]
   */
  public function getShards()
  {
    return $this->shards;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SourceSplitResponse::class, 'Google_Service_Dataflow_SourceSplitResponse');
