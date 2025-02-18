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

namespace Google\Service\AnalyticsReporting;

class GetReportsRequest extends \Google\Collection
{
  protected $collection_key = 'reportRequests';
  protected $reportRequestsType = ReportRequest::class;
  protected $reportRequestsDataType = 'array';
  /**
   * @var bool
   */
  public $useResourceQuotas;

  /**
   * @param ReportRequest[]
   */
  public function setReportRequests($reportRequests)
  {
    $this->reportRequests = $reportRequests;
  }
  /**
   * @return ReportRequest[]
   */
  public function getReportRequests()
  {
    return $this->reportRequests;
  }
  /**
   * @param bool
   */
  public function setUseResourceQuotas($useResourceQuotas)
  {
    $this->useResourceQuotas = $useResourceQuotas;
  }
  /**
   * @return bool
   */
  public function getUseResourceQuotas()
  {
    return $this->useResourceQuotas;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GetReportsRequest::class, 'Google_Service_AnalyticsReporting_GetReportsRequest');
