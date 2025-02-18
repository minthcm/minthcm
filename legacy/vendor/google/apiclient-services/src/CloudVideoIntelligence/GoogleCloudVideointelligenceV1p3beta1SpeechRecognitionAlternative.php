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

namespace Google\Service\CloudVideoIntelligence;

class GoogleCloudVideointelligenceV1p3beta1SpeechRecognitionAlternative extends \Google\Collection
{
  protected $collection_key = 'words';
  /**
   * @var float
   */
  public $confidence;
  /**
   * @var string
   */
  public $transcript;
  protected $wordsType = GoogleCloudVideointelligenceV1p3beta1WordInfo::class;
  protected $wordsDataType = 'array';

  /**
   * @param float
   */
  public function setConfidence($confidence)
  {
    $this->confidence = $confidence;
  }
  /**
   * @return float
   */
  public function getConfidence()
  {
    return $this->confidence;
  }
  /**
   * @param string
   */
  public function setTranscript($transcript)
  {
    $this->transcript = $transcript;
  }
  /**
   * @return string
   */
  public function getTranscript()
  {
    return $this->transcript;
  }
  /**
   * @param GoogleCloudVideointelligenceV1p3beta1WordInfo[]
   */
  public function setWords($words)
  {
    $this->words = $words;
  }
  /**
   * @return GoogleCloudVideointelligenceV1p3beta1WordInfo[]
   */
  public function getWords()
  {
    return $this->words;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudVideointelligenceV1p3beta1SpeechRecognitionAlternative::class, 'Google_Service_CloudVideoIntelligence_GoogleCloudVideointelligenceV1p3beta1SpeechRecognitionAlternative');
