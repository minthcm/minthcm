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

namespace Google\Service\RemoteBuildExecution;

class BuildBazelRemoteExecutionV2CacheCapabilities extends \Google\Collection
{
  protected $collection_key = 'supportedCompressor';
  protected $actionCacheUpdateCapabilitiesType = BuildBazelRemoteExecutionV2ActionCacheUpdateCapabilities::class;
  protected $actionCacheUpdateCapabilitiesDataType = '';
  protected $cachePriorityCapabilitiesType = BuildBazelRemoteExecutionV2PriorityCapabilities::class;
  protected $cachePriorityCapabilitiesDataType = '';
  public $digestFunction;
  public $maxBatchTotalSizeBytes;
  public $supportedCompressor;
  public $symlinkAbsolutePathStrategy;

  /**
   * @param BuildBazelRemoteExecutionV2ActionCacheUpdateCapabilities
   */
  public function setActionCacheUpdateCapabilities(BuildBazelRemoteExecutionV2ActionCacheUpdateCapabilities $actionCacheUpdateCapabilities)
  {
    $this->actionCacheUpdateCapabilities = $actionCacheUpdateCapabilities;
  }
  /**
   * @return BuildBazelRemoteExecutionV2ActionCacheUpdateCapabilities
   */
  public function getActionCacheUpdateCapabilities()
  {
    return $this->actionCacheUpdateCapabilities;
  }
  /**
   * @param BuildBazelRemoteExecutionV2PriorityCapabilities
   */
  public function setCachePriorityCapabilities(BuildBazelRemoteExecutionV2PriorityCapabilities $cachePriorityCapabilities)
  {
    $this->cachePriorityCapabilities = $cachePriorityCapabilities;
  }
  /**
   * @return BuildBazelRemoteExecutionV2PriorityCapabilities
   */
  public function getCachePriorityCapabilities()
  {
    return $this->cachePriorityCapabilities;
  }
  public function setDigestFunction($digestFunction)
  {
    $this->digestFunction = $digestFunction;
  }
  public function getDigestFunction()
  {
    return $this->digestFunction;
  }
  public function setMaxBatchTotalSizeBytes($maxBatchTotalSizeBytes)
  {
    $this->maxBatchTotalSizeBytes = $maxBatchTotalSizeBytes;
  }
  public function getMaxBatchTotalSizeBytes()
  {
    return $this->maxBatchTotalSizeBytes;
  }
  public function setSupportedCompressor($supportedCompressor)
  {
    $this->supportedCompressor = $supportedCompressor;
  }
  public function getSupportedCompressor()
  {
    return $this->supportedCompressor;
  }
  public function setSymlinkAbsolutePathStrategy($symlinkAbsolutePathStrategy)
  {
    $this->symlinkAbsolutePathStrategy = $symlinkAbsolutePathStrategy;
  }
  public function getSymlinkAbsolutePathStrategy()
  {
    return $this->symlinkAbsolutePathStrategy;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(BuildBazelRemoteExecutionV2CacheCapabilities::class, 'Google_Service_RemoteBuildExecution_BuildBazelRemoteExecutionV2CacheCapabilities');
