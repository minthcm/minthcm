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

class ReportSummary extends \Google\Collection
{
  protected $collection_key = 'groupFindings';
  protected $allAssetsStatsType = ReportSummaryAssetAggregateStats::class;
  protected $allAssetsStatsDataType = '';
  protected $groupFindingsType = ReportSummaryGroupFinding::class;
  protected $groupFindingsDataType = 'array';

  /**
   * @param ReportSummaryAssetAggregateStats
   */
  public function setAllAssetsStats(ReportSummaryAssetAggregateStats $allAssetsStats)
  {
    $this->allAssetsStats = $allAssetsStats;
  }
  /**
   * @return ReportSummaryAssetAggregateStats
   */
  public function getAllAssetsStats()
  {
    return $this->allAssetsStats;
  }
  /**
   * @param ReportSummaryGroupFinding[]
   */
  public function setGroupFindings($groupFindings)
  {
    $this->groupFindings = $groupFindings;
  }
  /**
   * @return ReportSummaryGroupFinding[]
   */
  public function getGroupFindings()
  {
    return $this->groupFindings;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ReportSummary::class, 'Google_Service_MigrationCenterAPI_ReportSummary');
