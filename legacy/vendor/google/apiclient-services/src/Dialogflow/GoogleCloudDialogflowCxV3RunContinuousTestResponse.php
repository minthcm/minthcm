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

namespace Google\Service\Dialogflow;

class GoogleCloudDialogflowCxV3RunContinuousTestResponse extends \Google\Model
{
  protected $continuousTestResultType = GoogleCloudDialogflowCxV3ContinuousTestResult::class;
  protected $continuousTestResultDataType = '';

  /**
   * @param GoogleCloudDialogflowCxV3ContinuousTestResult
   */
  public function setContinuousTestResult(GoogleCloudDialogflowCxV3ContinuousTestResult $continuousTestResult)
  {
    $this->continuousTestResult = $continuousTestResult;
  }
  /**
   * @return GoogleCloudDialogflowCxV3ContinuousTestResult
   */
  public function getContinuousTestResult()
  {
    return $this->continuousTestResult;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDialogflowCxV3RunContinuousTestResponse::class, 'Google_Service_Dialogflow_GoogleCloudDialogflowCxV3RunContinuousTestResponse');
