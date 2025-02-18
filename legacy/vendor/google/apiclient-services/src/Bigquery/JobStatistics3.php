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

namespace Google\Service\Bigquery;

class JobStatistics3 extends \Google\Collection
{
  protected $collection_key = 'timeline';
  /**
   * @var string
   */
  public $badRecords;
  /**
   * @var string
   */
  public $inputFileBytes;
  /**
   * @var string
   */
  public $inputFiles;
  /**
   * @var string
   */
  public $outputBytes;
  /**
   * @var string
   */
  public $outputRows;
  protected $timelineType = QueryTimelineSample::class;
  protected $timelineDataType = 'array';

  /**
   * @param string
   */
  public function setBadRecords($badRecords)
  {
    $this->badRecords = $badRecords;
  }
  /**
   * @return string
   */
  public function getBadRecords()
  {
    return $this->badRecords;
  }
  /**
   * @param string
   */
  public function setInputFileBytes($inputFileBytes)
  {
    $this->inputFileBytes = $inputFileBytes;
  }
  /**
   * @return string
   */
  public function getInputFileBytes()
  {
    return $this->inputFileBytes;
  }
  /**
   * @param string
   */
  public function setInputFiles($inputFiles)
  {
    $this->inputFiles = $inputFiles;
  }
  /**
   * @return string
   */
  public function getInputFiles()
  {
    return $this->inputFiles;
  }
  /**
   * @param string
   */
  public function setOutputBytes($outputBytes)
  {
    $this->outputBytes = $outputBytes;
  }
  /**
   * @return string
   */
  public function getOutputBytes()
  {
    return $this->outputBytes;
  }
  /**
   * @param string
   */
  public function setOutputRows($outputRows)
  {
    $this->outputRows = $outputRows;
  }
  /**
   * @return string
   */
  public function getOutputRows()
  {
    return $this->outputRows;
  }
  /**
   * @param QueryTimelineSample[]
   */
  public function setTimeline($timeline)
  {
    $this->timeline = $timeline;
  }
  /**
   * @return QueryTimelineSample[]
   */
  public function getTimeline()
  {
    return $this->timeline;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(JobStatistics3::class, 'Google_Service_Bigquery_JobStatistics3');
