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

namespace Google\Service\Assuredworkloads;

class GoogleCloudAssuredworkloadsV1beta1WorkloadFedrampHighSettings extends \Google\Model
{
  protected $kmsSettingsType = GoogleCloudAssuredworkloadsV1beta1WorkloadKMSSettings::class;
  protected $kmsSettingsDataType = '';

  /**
   * @param GoogleCloudAssuredworkloadsV1beta1WorkloadKMSSettings
   */
  public function setKmsSettings(GoogleCloudAssuredworkloadsV1beta1WorkloadKMSSettings $kmsSettings)
  {
    $this->kmsSettings = $kmsSettings;
  }
  /**
   * @return GoogleCloudAssuredworkloadsV1beta1WorkloadKMSSettings
   */
  public function getKmsSettings()
  {
    return $this->kmsSettings;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAssuredworkloadsV1beta1WorkloadFedrampHighSettings::class, 'Google_Service_Assuredworkloads_GoogleCloudAssuredworkloadsV1beta1WorkloadFedrampHighSettings');
