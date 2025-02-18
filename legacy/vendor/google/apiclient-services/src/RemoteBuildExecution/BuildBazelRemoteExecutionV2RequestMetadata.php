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

class BuildBazelRemoteExecutionV2RequestMetadata extends \Google\Model
{
  public $actionId;
  public $actionMnemonic;
  public $configurationId;
  public $correlatedInvocationsId;
  public $targetId;
  protected $toolDetailsType = BuildBazelRemoteExecutionV2ToolDetails::class;
  protected $toolDetailsDataType = '';
  public $toolInvocationId;

  public function setActionId($actionId)
  {
    $this->actionId = $actionId;
  }
  public function getActionId()
  {
    return $this->actionId;
  }
  public function setActionMnemonic($actionMnemonic)
  {
    $this->actionMnemonic = $actionMnemonic;
  }
  public function getActionMnemonic()
  {
    return $this->actionMnemonic;
  }
  public function setConfigurationId($configurationId)
  {
    $this->configurationId = $configurationId;
  }
  public function getConfigurationId()
  {
    return $this->configurationId;
  }
  public function setCorrelatedInvocationsId($correlatedInvocationsId)
  {
    $this->correlatedInvocationsId = $correlatedInvocationsId;
  }
  public function getCorrelatedInvocationsId()
  {
    return $this->correlatedInvocationsId;
  }
  public function setTargetId($targetId)
  {
    $this->targetId = $targetId;
  }
  public function getTargetId()
  {
    return $this->targetId;
  }
  /**
   * @param BuildBazelRemoteExecutionV2ToolDetails
   */
  public function setToolDetails(BuildBazelRemoteExecutionV2ToolDetails $toolDetails)
  {
    $this->toolDetails = $toolDetails;
  }
  /**
   * @return BuildBazelRemoteExecutionV2ToolDetails
   */
  public function getToolDetails()
  {
    return $this->toolDetails;
  }
  public function setToolInvocationId($toolInvocationId)
  {
    $this->toolInvocationId = $toolInvocationId;
  }
  public function getToolInvocationId()
  {
    return $this->toolInvocationId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(BuildBazelRemoteExecutionV2RequestMetadata::class, 'Google_Service_RemoteBuildExecution_BuildBazelRemoteExecutionV2RequestMetadata');
