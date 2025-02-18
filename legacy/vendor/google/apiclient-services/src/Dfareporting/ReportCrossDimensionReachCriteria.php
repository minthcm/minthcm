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

namespace Google\Service\Dfareporting;

class ReportCrossDimensionReachCriteria extends \Google\Collection
{
  protected $collection_key = 'overlapMetricNames';
  protected $breakdownType = SortedDimension::class;
  protected $breakdownDataType = 'array';
  protected $dateRangeType = DateRange::class;
  protected $dateRangeDataType = '';
  /**
   * @var string
   */
  public $dimension;
  protected $dimensionFiltersType = DimensionValue::class;
  protected $dimensionFiltersDataType = 'array';
  /**
   * @var string[]
   */
  public $metricNames;
  /**
   * @var string[]
   */
  public $overlapMetricNames;
  /**
   * @var bool
   */
  public $pivoted;

  /**
   * @param SortedDimension[]
   */
  public function setBreakdown($breakdown)
  {
    $this->breakdown = $breakdown;
  }
  /**
   * @return SortedDimension[]
   */
  public function getBreakdown()
  {
    return $this->breakdown;
  }
  /**
   * @param DateRange
   */
  public function setDateRange(DateRange $dateRange)
  {
    $this->dateRange = $dateRange;
  }
  /**
   * @return DateRange
   */
  public function getDateRange()
  {
    return $this->dateRange;
  }
  /**
   * @param string
   */
  public function setDimension($dimension)
  {
    $this->dimension = $dimension;
  }
  /**
   * @return string
   */
  public function getDimension()
  {
    return $this->dimension;
  }
  /**
   * @param DimensionValue[]
   */
  public function setDimensionFilters($dimensionFilters)
  {
    $this->dimensionFilters = $dimensionFilters;
  }
  /**
   * @return DimensionValue[]
   */
  public function getDimensionFilters()
  {
    return $this->dimensionFilters;
  }
  /**
   * @param string[]
   */
  public function setMetricNames($metricNames)
  {
    $this->metricNames = $metricNames;
  }
  /**
   * @return string[]
   */
  public function getMetricNames()
  {
    return $this->metricNames;
  }
  /**
   * @param string[]
   */
  public function setOverlapMetricNames($overlapMetricNames)
  {
    $this->overlapMetricNames = $overlapMetricNames;
  }
  /**
   * @return string[]
   */
  public function getOverlapMetricNames()
  {
    return $this->overlapMetricNames;
  }
  /**
   * @param bool
   */
  public function setPivoted($pivoted)
  {
    $this->pivoted = $pivoted;
  }
  /**
   * @return bool
   */
  public function getPivoted()
  {
    return $this->pivoted;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ReportCrossDimensionReachCriteria::class, 'Google_Service_Dfareporting_ReportCrossDimensionReachCriteria');
