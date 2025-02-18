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

namespace Google\Service\Cloudchannel;

class GoogleCloudChannelV1alpha1ReportResultsMetadata extends \Google\Model
{
  protected $dateRangeType = GoogleCloudChannelV1alpha1DateRange::class;
  protected $dateRangeDataType = '';
  protected $precedingDateRangeType = GoogleCloudChannelV1alpha1DateRange::class;
  protected $precedingDateRangeDataType = '';
  protected $reportType = GoogleCloudChannelV1alpha1Report::class;
  protected $reportDataType = '';
  /**
   * @var string
   */
  public $rowCount;

  /**
   * @param GoogleCloudChannelV1alpha1DateRange
   */
  public function setDateRange(GoogleCloudChannelV1alpha1DateRange $dateRange)
  {
    $this->dateRange = $dateRange;
  }
  /**
   * @return GoogleCloudChannelV1alpha1DateRange
   */
  public function getDateRange()
  {
    return $this->dateRange;
  }
  /**
   * @param GoogleCloudChannelV1alpha1DateRange
   */
  public function setPrecedingDateRange(GoogleCloudChannelV1alpha1DateRange $precedingDateRange)
  {
    $this->precedingDateRange = $precedingDateRange;
  }
  /**
   * @return GoogleCloudChannelV1alpha1DateRange
   */
  public function getPrecedingDateRange()
  {
    return $this->precedingDateRange;
  }
  /**
   * @param GoogleCloudChannelV1alpha1Report
   */
  public function setReport(GoogleCloudChannelV1alpha1Report $report)
  {
    $this->report = $report;
  }
  /**
   * @return GoogleCloudChannelV1alpha1Report
   */
  public function getReport()
  {
    return $this->report;
  }
  /**
   * @param string
   */
  public function setRowCount($rowCount)
  {
    $this->rowCount = $rowCount;
  }
  /**
   * @return string
   */
  public function getRowCount()
  {
    return $this->rowCount;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudChannelV1alpha1ReportResultsMetadata::class, 'Google_Service_Cloudchannel_GoogleCloudChannelV1alpha1ReportResultsMetadata');
