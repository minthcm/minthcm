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

class XPSSpeechPreprocessResponse extends \Google\Model
{
  /**
   * @var string
   */
  public $cnsTestDataPath;
  /**
   * @var string
   */
  public $cnsTrainDataPath;
  protected $prebuiltModelEvaluationMetricsType = XPSSpeechEvaluationMetrics::class;
  protected $prebuiltModelEvaluationMetricsDataType = '';
  protected $speechPreprocessStatsType = XPSSpeechPreprocessStats::class;
  protected $speechPreprocessStatsDataType = '';

  /**
   * @param string
   */
  public function setCnsTestDataPath($cnsTestDataPath)
  {
    $this->cnsTestDataPath = $cnsTestDataPath;
  }
  /**
   * @return string
   */
  public function getCnsTestDataPath()
  {
    return $this->cnsTestDataPath;
  }
  /**
   * @param string
   */
  public function setCnsTrainDataPath($cnsTrainDataPath)
  {
    $this->cnsTrainDataPath = $cnsTrainDataPath;
  }
  /**
   * @return string
   */
  public function getCnsTrainDataPath()
  {
    return $this->cnsTrainDataPath;
  }
  /**
   * @param XPSSpeechEvaluationMetrics
   */
  public function setPrebuiltModelEvaluationMetrics(XPSSpeechEvaluationMetrics $prebuiltModelEvaluationMetrics)
  {
    $this->prebuiltModelEvaluationMetrics = $prebuiltModelEvaluationMetrics;
  }
  /**
   * @return XPSSpeechEvaluationMetrics
   */
  public function getPrebuiltModelEvaluationMetrics()
  {
    return $this->prebuiltModelEvaluationMetrics;
  }
  /**
   * @param XPSSpeechPreprocessStats
   */
  public function setSpeechPreprocessStats(XPSSpeechPreprocessStats $speechPreprocessStats)
  {
    $this->speechPreprocessStats = $speechPreprocessStats;
  }
  /**
   * @return XPSSpeechPreprocessStats
   */
  public function getSpeechPreprocessStats()
  {
    return $this->speechPreprocessStats;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(XPSSpeechPreprocessResponse::class, 'Google_Service_CloudNaturalLanguage_XPSSpeechPreprocessResponse');
