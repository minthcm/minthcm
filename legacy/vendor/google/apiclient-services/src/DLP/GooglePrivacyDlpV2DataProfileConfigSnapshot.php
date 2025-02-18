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

namespace Google\Service\DLP;

class GooglePrivacyDlpV2DataProfileConfigSnapshot extends \Google\Model
{
  protected $dataProfileJobType = GooglePrivacyDlpV2DataProfileJobConfig::class;
  protected $dataProfileJobDataType = '';
  protected $discoveryConfigType = GooglePrivacyDlpV2DiscoveryConfig::class;
  protected $discoveryConfigDataType = '';
  protected $inspectConfigType = GooglePrivacyDlpV2InspectConfig::class;
  protected $inspectConfigDataType = '';
  /**
   * @var string
   */
  public $inspectTemplateModifiedTime;
  /**
   * @var string
   */
  public $inspectTemplateName;

  /**
   * @param GooglePrivacyDlpV2DataProfileJobConfig
   */
  public function setDataProfileJob(GooglePrivacyDlpV2DataProfileJobConfig $dataProfileJob)
  {
    $this->dataProfileJob = $dataProfileJob;
  }
  /**
   * @return GooglePrivacyDlpV2DataProfileJobConfig
   */
  public function getDataProfileJob()
  {
    return $this->dataProfileJob;
  }
  /**
   * @param GooglePrivacyDlpV2DiscoveryConfig
   */
  public function setDiscoveryConfig(GooglePrivacyDlpV2DiscoveryConfig $discoveryConfig)
  {
    $this->discoveryConfig = $discoveryConfig;
  }
  /**
   * @return GooglePrivacyDlpV2DiscoveryConfig
   */
  public function getDiscoveryConfig()
  {
    return $this->discoveryConfig;
  }
  /**
   * @param GooglePrivacyDlpV2InspectConfig
   */
  public function setInspectConfig(GooglePrivacyDlpV2InspectConfig $inspectConfig)
  {
    $this->inspectConfig = $inspectConfig;
  }
  /**
   * @return GooglePrivacyDlpV2InspectConfig
   */
  public function getInspectConfig()
  {
    return $this->inspectConfig;
  }
  /**
   * @param string
   */
  public function setInspectTemplateModifiedTime($inspectTemplateModifiedTime)
  {
    $this->inspectTemplateModifiedTime = $inspectTemplateModifiedTime;
  }
  /**
   * @return string
   */
  public function getInspectTemplateModifiedTime()
  {
    return $this->inspectTemplateModifiedTime;
  }
  /**
   * @param string
   */
  public function setInspectTemplateName($inspectTemplateName)
  {
    $this->inspectTemplateName = $inspectTemplateName;
  }
  /**
   * @return string
   */
  public function getInspectTemplateName()
  {
    return $this->inspectTemplateName;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GooglePrivacyDlpV2DataProfileConfigSnapshot::class, 'Google_Service_DLP_GooglePrivacyDlpV2DataProfileConfigSnapshot');
