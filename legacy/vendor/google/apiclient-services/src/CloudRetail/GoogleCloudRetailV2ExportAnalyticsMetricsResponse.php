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

namespace Google\Service\CloudRetail;

class GoogleCloudRetailV2ExportAnalyticsMetricsResponse extends \Google\Collection
{
  protected $collection_key = 'errorSamples';
  protected $errorSamplesType = GoogleRpcStatus::class;
  protected $errorSamplesDataType = 'array';
  protected $errorsConfigType = GoogleCloudRetailV2ExportErrorsConfig::class;
  protected $errorsConfigDataType = '';
  protected $outputResultType = GoogleCloudRetailV2OutputResult::class;
  protected $outputResultDataType = '';

  /**
   * @param GoogleRpcStatus[]
   */
  public function setErrorSamples($errorSamples)
  {
    $this->errorSamples = $errorSamples;
  }
  /**
   * @return GoogleRpcStatus[]
   */
  public function getErrorSamples()
  {
    return $this->errorSamples;
  }
  /**
   * @param GoogleCloudRetailV2ExportErrorsConfig
   */
  public function setErrorsConfig(GoogleCloudRetailV2ExportErrorsConfig $errorsConfig)
  {
    $this->errorsConfig = $errorsConfig;
  }
  /**
   * @return GoogleCloudRetailV2ExportErrorsConfig
   */
  public function getErrorsConfig()
  {
    return $this->errorsConfig;
  }
  /**
   * @param GoogleCloudRetailV2OutputResult
   */
  public function setOutputResult(GoogleCloudRetailV2OutputResult $outputResult)
  {
    $this->outputResult = $outputResult;
  }
  /**
   * @return GoogleCloudRetailV2OutputResult
   */
  public function getOutputResult()
  {
    return $this->outputResult;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudRetailV2ExportAnalyticsMetricsResponse::class, 'Google_Service_CloudRetail_GoogleCloudRetailV2ExportAnalyticsMetricsResponse');
