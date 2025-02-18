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

class StreamingSetupTask extends \Google\Model
{
  /**
   * @var bool
   */
  public $drain;
  /**
   * @var int
   */
  public $receiveWorkPort;
  protected $snapshotConfigType = StreamingApplianceSnapshotConfig::class;
  protected $snapshotConfigDataType = '';
  protected $streamingComputationTopologyType = TopologyConfig::class;
  protected $streamingComputationTopologyDataType = '';
  /**
   * @var int
   */
  public $workerHarnessPort;

  /**
   * @param bool
   */
  public function setDrain($drain)
  {
    $this->drain = $drain;
  }
  /**
   * @return bool
   */
  public function getDrain()
  {
    return $this->drain;
  }
  /**
   * @param int
   */
  public function setReceiveWorkPort($receiveWorkPort)
  {
    $this->receiveWorkPort = $receiveWorkPort;
  }
  /**
   * @return int
   */
  public function getReceiveWorkPort()
  {
    return $this->receiveWorkPort;
  }
  /**
   * @param StreamingApplianceSnapshotConfig
   */
  public function setSnapshotConfig(StreamingApplianceSnapshotConfig $snapshotConfig)
  {
    $this->snapshotConfig = $snapshotConfig;
  }
  /**
   * @return StreamingApplianceSnapshotConfig
   */
  public function getSnapshotConfig()
  {
    return $this->snapshotConfig;
  }
  /**
   * @param TopologyConfig
   */
  public function setStreamingComputationTopology(TopologyConfig $streamingComputationTopology)
  {
    $this->streamingComputationTopology = $streamingComputationTopology;
  }
  /**
   * @return TopologyConfig
   */
  public function getStreamingComputationTopology()
  {
    return $this->streamingComputationTopology;
  }
  /**
   * @param int
   */
  public function setWorkerHarnessPort($workerHarnessPort)
  {
    $this->workerHarnessPort = $workerHarnessPort;
  }
  /**
   * @return int
   */
  public function getWorkerHarnessPort()
  {
    return $this->workerHarnessPort;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(StreamingSetupTask::class, 'Google_Service_Dataflow_StreamingSetupTask');
