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

class GoogleCloudDialogflowCxV3beta1AdvancedSettings extends \Google\Model
{
  protected $audioExportGcsDestinationType = GoogleCloudDialogflowCxV3beta1GcsDestination::class;
  protected $audioExportGcsDestinationDataType = '';
  protected $dtmfSettingsType = GoogleCloudDialogflowCxV3beta1AdvancedSettingsDtmfSettings::class;
  protected $dtmfSettingsDataType = '';
  protected $loggingSettingsType = GoogleCloudDialogflowCxV3beta1AdvancedSettingsLoggingSettings::class;
  protected $loggingSettingsDataType = '';
  protected $speechSettingsType = GoogleCloudDialogflowCxV3beta1AdvancedSettingsSpeechSettings::class;
  protected $speechSettingsDataType = '';

  /**
   * @param GoogleCloudDialogflowCxV3beta1GcsDestination
   */
  public function setAudioExportGcsDestination(GoogleCloudDialogflowCxV3beta1GcsDestination $audioExportGcsDestination)
  {
    $this->audioExportGcsDestination = $audioExportGcsDestination;
  }
  /**
   * @return GoogleCloudDialogflowCxV3beta1GcsDestination
   */
  public function getAudioExportGcsDestination()
  {
    return $this->audioExportGcsDestination;
  }
  /**
   * @param GoogleCloudDialogflowCxV3beta1AdvancedSettingsDtmfSettings
   */
  public function setDtmfSettings(GoogleCloudDialogflowCxV3beta1AdvancedSettingsDtmfSettings $dtmfSettings)
  {
    $this->dtmfSettings = $dtmfSettings;
  }
  /**
   * @return GoogleCloudDialogflowCxV3beta1AdvancedSettingsDtmfSettings
   */
  public function getDtmfSettings()
  {
    return $this->dtmfSettings;
  }
  /**
   * @param GoogleCloudDialogflowCxV3beta1AdvancedSettingsLoggingSettings
   */
  public function setLoggingSettings(GoogleCloudDialogflowCxV3beta1AdvancedSettingsLoggingSettings $loggingSettings)
  {
    $this->loggingSettings = $loggingSettings;
  }
  /**
   * @return GoogleCloudDialogflowCxV3beta1AdvancedSettingsLoggingSettings
   */
  public function getLoggingSettings()
  {
    return $this->loggingSettings;
  }
  /**
   * @param GoogleCloudDialogflowCxV3beta1AdvancedSettingsSpeechSettings
   */
  public function setSpeechSettings(GoogleCloudDialogflowCxV3beta1AdvancedSettingsSpeechSettings $speechSettings)
  {
    $this->speechSettings = $speechSettings;
  }
  /**
   * @return GoogleCloudDialogflowCxV3beta1AdvancedSettingsSpeechSettings
   */
  public function getSpeechSettings()
  {
    return $this->speechSettings;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDialogflowCxV3beta1AdvancedSettings::class, 'Google_Service_Dialogflow_GoogleCloudDialogflowCxV3beta1AdvancedSettings');
