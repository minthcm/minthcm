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

namespace Google\Service\CloudSearch;

class DataLossPreventionMetadata extends \Google\Model
{
  protected $dlpMessageScanRecordType = DlpMessageScanRecord::class;
  protected $dlpMessageScanRecordDataType = '';
  protected $dlpScanSummaryType = DlpScanSummary::class;
  protected $dlpScanSummaryDataType = '';
  /**
   * @var bool
   */
  public $warnAcknowledged;

  /**
   * @param DlpMessageScanRecord
   */
  public function setDlpMessageScanRecord(DlpMessageScanRecord $dlpMessageScanRecord)
  {
    $this->dlpMessageScanRecord = $dlpMessageScanRecord;
  }
  /**
   * @return DlpMessageScanRecord
   */
  public function getDlpMessageScanRecord()
  {
    return $this->dlpMessageScanRecord;
  }
  /**
   * @param DlpScanSummary
   */
  public function setDlpScanSummary(DlpScanSummary $dlpScanSummary)
  {
    $this->dlpScanSummary = $dlpScanSummary;
  }
  /**
   * @return DlpScanSummary
   */
  public function getDlpScanSummary()
  {
    return $this->dlpScanSummary;
  }
  /**
   * @param bool
   */
  public function setWarnAcknowledged($warnAcknowledged)
  {
    $this->warnAcknowledged = $warnAcknowledged;
  }
  /**
   * @return bool
   */
  public function getWarnAcknowledged()
  {
    return $this->warnAcknowledged;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DataLossPreventionMetadata::class, 'Google_Service_CloudSearch_DataLossPreventionMetadata');
