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

class GooglePrivacyDlpV2ReidentifyContentRequest extends \Google\Model
{
  protected $inspectConfigType = GooglePrivacyDlpV2InspectConfig::class;
  protected $inspectConfigDataType = '';
  /**
   * @var string
   */
  public $inspectTemplateName;
  protected $itemType = GooglePrivacyDlpV2ContentItem::class;
  protected $itemDataType = '';
  /**
   * @var string
   */
  public $locationId;
  protected $reidentifyConfigType = GooglePrivacyDlpV2DeidentifyConfig::class;
  protected $reidentifyConfigDataType = '';
  /**
   * @var string
   */
  public $reidentifyTemplateName;

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
  /**
   * @param GooglePrivacyDlpV2ContentItem
   */
  public function setItem(GooglePrivacyDlpV2ContentItem $item)
  {
    $this->item = $item;
  }
  /**
   * @return GooglePrivacyDlpV2ContentItem
   */
  public function getItem()
  {
    return $this->item;
  }
  /**
   * @param string
   */
  public function setLocationId($locationId)
  {
    $this->locationId = $locationId;
  }
  /**
   * @return string
   */
  public function getLocationId()
  {
    return $this->locationId;
  }
  /**
   * @param GooglePrivacyDlpV2DeidentifyConfig
   */
  public function setReidentifyConfig(GooglePrivacyDlpV2DeidentifyConfig $reidentifyConfig)
  {
    $this->reidentifyConfig = $reidentifyConfig;
  }
  /**
   * @return GooglePrivacyDlpV2DeidentifyConfig
   */
  public function getReidentifyConfig()
  {
    return $this->reidentifyConfig;
  }
  /**
   * @param string
   */
  public function setReidentifyTemplateName($reidentifyTemplateName)
  {
    $this->reidentifyTemplateName = $reidentifyTemplateName;
  }
  /**
   * @return string
   */
  public function getReidentifyTemplateName()
  {
    return $this->reidentifyTemplateName;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GooglePrivacyDlpV2ReidentifyContentRequest::class, 'Google_Service_DLP_GooglePrivacyDlpV2ReidentifyContentRequest');
