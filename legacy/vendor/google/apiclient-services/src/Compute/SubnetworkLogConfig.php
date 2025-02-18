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

namespace Google\Service\Compute;

class SubnetworkLogConfig extends \Google\Collection
{
  protected $collection_key = 'metadataFields';
  /**
   * @var string
   */
  public $aggregationInterval;
  /**
   * @var bool
   */
  public $enable;
  /**
   * @var string
   */
  public $filterExpr;
  /**
   * @var float
   */
  public $flowSampling;
  /**
   * @var string
   */
  public $metadata;
  /**
   * @var string[]
   */
  public $metadataFields;

  /**
   * @param string
   */
  public function setAggregationInterval($aggregationInterval)
  {
    $this->aggregationInterval = $aggregationInterval;
  }
  /**
   * @return string
   */
  public function getAggregationInterval()
  {
    return $this->aggregationInterval;
  }
  /**
   * @param bool
   */
  public function setEnable($enable)
  {
    $this->enable = $enable;
  }
  /**
   * @return bool
   */
  public function getEnable()
  {
    return $this->enable;
  }
  /**
   * @param string
   */
  public function setFilterExpr($filterExpr)
  {
    $this->filterExpr = $filterExpr;
  }
  /**
   * @return string
   */
  public function getFilterExpr()
  {
    return $this->filterExpr;
  }
  /**
   * @param float
   */
  public function setFlowSampling($flowSampling)
  {
    $this->flowSampling = $flowSampling;
  }
  /**
   * @return float
   */
  public function getFlowSampling()
  {
    return $this->flowSampling;
  }
  /**
   * @param string
   */
  public function setMetadata($metadata)
  {
    $this->metadata = $metadata;
  }
  /**
   * @return string
   */
  public function getMetadata()
  {
    return $this->metadata;
  }
  /**
   * @param string[]
   */
  public function setMetadataFields($metadataFields)
  {
    $this->metadataFields = $metadataFields;
  }
  /**
   * @return string[]
   */
  public function getMetadataFields()
  {
    return $this->metadataFields;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SubnetworkLogConfig::class, 'Google_Service_Compute_SubnetworkLogConfig');
