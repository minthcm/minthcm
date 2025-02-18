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

namespace Google\Service\Monitoring;

class QueryTimeSeriesResponse extends \Google\Collection
{
  protected $collection_key = 'timeSeriesData';
  /**
   * @var string
   */
  public $nextPageToken;
  protected $partialErrorsType = Status::class;
  protected $partialErrorsDataType = 'array';
  protected $timeSeriesDataType = TimeSeriesData::class;
  protected $timeSeriesDataDataType = 'array';
  protected $timeSeriesDescriptorType = TimeSeriesDescriptor::class;
  protected $timeSeriesDescriptorDataType = '';

  /**
   * @param string
   */
  public function setNextPageToken($nextPageToken)
  {
    $this->nextPageToken = $nextPageToken;
  }
  /**
   * @return string
   */
  public function getNextPageToken()
  {
    return $this->nextPageToken;
  }
  /**
   * @param Status[]
   */
  public function setPartialErrors($partialErrors)
  {
    $this->partialErrors = $partialErrors;
  }
  /**
   * @return Status[]
   */
  public function getPartialErrors()
  {
    return $this->partialErrors;
  }
  /**
   * @param TimeSeriesData[]
   */
  public function setTimeSeriesData($timeSeriesData)
  {
    $this->timeSeriesData = $timeSeriesData;
  }
  /**
   * @return TimeSeriesData[]
   */
  public function getTimeSeriesData()
  {
    return $this->timeSeriesData;
  }
  /**
   * @param TimeSeriesDescriptor
   */
  public function setTimeSeriesDescriptor(TimeSeriesDescriptor $timeSeriesDescriptor)
  {
    $this->timeSeriesDescriptor = $timeSeriesDescriptor;
  }
  /**
   * @return TimeSeriesDescriptor
   */
  public function getTimeSeriesDescriptor()
  {
    return $this->timeSeriesDescriptor;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(QueryTimeSeriesResponse::class, 'Google_Service_Monitoring_QueryTimeSeriesResponse');
