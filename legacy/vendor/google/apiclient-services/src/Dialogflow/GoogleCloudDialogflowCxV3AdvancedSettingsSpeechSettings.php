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

class GoogleCloudDialogflowCxV3AdvancedSettingsSpeechSettings extends \Google\Model
{
  /**
   * @var int
   */
  public $endpointerSensitivity;
  /**
   * @var string[]
   */
  public $models;
  /**
   * @var string
   */
  public $noSpeechTimeout;
  /**
   * @var bool
   */
  public $useTimeoutBasedEndpointing;

  /**
   * @param int
   */
  public function setEndpointerSensitivity($endpointerSensitivity)
  {
    $this->endpointerSensitivity = $endpointerSensitivity;
  }
  /**
   * @return int
   */
  public function getEndpointerSensitivity()
  {
    return $this->endpointerSensitivity;
  }
  /**
   * @param string[]
   */
  public function setModels($models)
  {
    $this->models = $models;
  }
  /**
   * @return string[]
   */
  public function getModels()
  {
    return $this->models;
  }
  /**
   * @param string
   */
  public function setNoSpeechTimeout($noSpeechTimeout)
  {
    $this->noSpeechTimeout = $noSpeechTimeout;
  }
  /**
   * @return string
   */
  public function getNoSpeechTimeout()
  {
    return $this->noSpeechTimeout;
  }
  /**
   * @param bool
   */
  public function setUseTimeoutBasedEndpointing($useTimeoutBasedEndpointing)
  {
    $this->useTimeoutBasedEndpointing = $useTimeoutBasedEndpointing;
  }
  /**
   * @return bool
   */
  public function getUseTimeoutBasedEndpointing()
  {
    return $this->useTimeoutBasedEndpointing;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDialogflowCxV3AdvancedSettingsSpeechSettings::class, 'Google_Service_Dialogflow_GoogleCloudDialogflowCxV3AdvancedSettingsSpeechSettings');
