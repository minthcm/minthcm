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

namespace Google\Service\MigrationCenterAPI;

class ReportSummarySoleTenantNodeAllocation extends \Google\Model
{
  /**
   * @var string
   */
  public $allocatedAssetCount;
  protected $nodeType = SoleTenantNodeType::class;
  protected $nodeDataType = '';
  /**
   * @var string
   */
  public $nodeCount;

  /**
   * @param string
   */
  public function setAllocatedAssetCount($allocatedAssetCount)
  {
    $this->allocatedAssetCount = $allocatedAssetCount;
  }
  /**
   * @return string
   */
  public function getAllocatedAssetCount()
  {
    return $this->allocatedAssetCount;
  }
  /**
   * @param SoleTenantNodeType
   */
  public function setNode(SoleTenantNodeType $node)
  {
    $this->node = $node;
  }
  /**
   * @return SoleTenantNodeType
   */
  public function getNode()
  {
    return $this->node;
  }
  /**
   * @param string
   */
  public function setNodeCount($nodeCount)
  {
    $this->nodeCount = $nodeCount;
  }
  /**
   * @return string
   */
  public function getNodeCount()
  {
    return $this->nodeCount;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ReportSummarySoleTenantNodeAllocation::class, 'Google_Service_MigrationCenterAPI_ReportSummarySoleTenantNodeAllocation');
