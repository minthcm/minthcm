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

namespace Google\Service\Speech;

class RecognizeResponse extends \Google\Collection
{
  protected $collection_key = 'results';
  /**
   * @var string
   */
  public $requestId;
  protected $resultsType = SpeechRecognitionResult::class;
  protected $resultsDataType = 'array';
  protected $speechAdaptationInfoType = SpeechAdaptationInfo::class;
  protected $speechAdaptationInfoDataType = '';
  /**
   * @var string
   */
  public $totalBilledTime;
  /**
   * @var bool
   */
  public $usingLegacyModels;

  /**
   * @param string
   */
  public function setRequestId($requestId)
  {
    $this->requestId = $requestId;
  }
  /**
   * @return string
   */
  public function getRequestId()
  {
    return $this->requestId;
  }
  /**
   * @param SpeechRecognitionResult[]
   */
  public function setResults($results)
  {
    $this->results = $results;
  }
  /**
   * @return SpeechRecognitionResult[]
   */
  public function getResults()
  {
    return $this->results;
  }
  /**
   * @param SpeechAdaptationInfo
   */
  public function setSpeechAdaptationInfo(SpeechAdaptationInfo $speechAdaptationInfo)
  {
    $this->speechAdaptationInfo = $speechAdaptationInfo;
  }
  /**
   * @return SpeechAdaptationInfo
   */
  public function getSpeechAdaptationInfo()
  {
    return $this->speechAdaptationInfo;
  }
  /**
   * @param string
   */
  public function setTotalBilledTime($totalBilledTime)
  {
    $this->totalBilledTime = $totalBilledTime;
  }
  /**
   * @return string
   */
  public function getTotalBilledTime()
  {
    return $this->totalBilledTime;
  }
  /**
   * @param bool
   */
  public function setUsingLegacyModels($usingLegacyModels)
  {
    $this->usingLegacyModels = $usingLegacyModels;
  }
  /**
   * @return bool
   */
  public function getUsingLegacyModels()
  {
    return $this->usingLegacyModels;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(RecognizeResponse::class, 'Google_Service_Speech_RecognizeResponse');
