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

namespace Google\Service\CloudNaturalLanguage;

class XPSFloat64Stats extends \Google\Collection
{
  protected $collection_key = 'quantiles';
  protected $commonStatsType = XPSCommonStats::class;
  protected $commonStatsDataType = '';
  protected $histogramBucketsType = XPSFloat64StatsHistogramBucket::class;
  protected $histogramBucketsDataType = 'array';
  public $mean;
  public $quantiles;
  public $standardDeviation;

  /**
   * @param XPSCommonStats
   */
  public function setCommonStats(XPSCommonStats $commonStats)
  {
    $this->commonStats = $commonStats;
  }
  /**
   * @return XPSCommonStats
   */
  public function getCommonStats()
  {
    return $this->commonStats;
  }
  /**
   * @param XPSFloat64StatsHistogramBucket[]
   */
  public function setHistogramBuckets($histogramBuckets)
  {
    $this->histogramBuckets = $histogramBuckets;
  }
  /**
   * @return XPSFloat64StatsHistogramBucket[]
   */
  public function getHistogramBuckets()
  {
    return $this->histogramBuckets;
  }
  public function setMean($mean)
  {
    $this->mean = $mean;
  }
  public function getMean()
  {
    return $this->mean;
  }
  public function setQuantiles($quantiles)
  {
    $this->quantiles = $quantiles;
  }
  public function getQuantiles()
  {
    return $this->quantiles;
  }
  public function setStandardDeviation($standardDeviation)
  {
    $this->standardDeviation = $standardDeviation;
  }
  public function getStandardDeviation()
  {
    return $this->standardDeviation;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(XPSFloat64Stats::class, 'Google_Service_CloudNaturalLanguage_XPSFloat64Stats');
