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

namespace Google\Service\Dfareporting;

class DirectorySiteSettings extends \Google\Model
{
  /**
   * @var bool
   */
  public $activeViewOptOut;
  protected $dfpSettingsType = DfpSettings::class;
  protected $dfpSettingsDataType = '';
  /**
   * @var bool
   */
  public $instreamVideoPlacementAccepted;
  /**
   * @var bool
   */
  public $interstitialPlacementAccepted;

  /**
   * @param bool
   */
  public function setActiveViewOptOut($activeViewOptOut)
  {
    $this->activeViewOptOut = $activeViewOptOut;
  }
  /**
   * @return bool
   */
  public function getActiveViewOptOut()
  {
    return $this->activeViewOptOut;
  }
  /**
   * @param DfpSettings
   */
  public function setDfpSettings(DfpSettings $dfpSettings)
  {
    $this->dfpSettings = $dfpSettings;
  }
  /**
   * @return DfpSettings
   */
  public function getDfpSettings()
  {
    return $this->dfpSettings;
  }
  /**
   * @param bool
   */
  public function setInstreamVideoPlacementAccepted($instreamVideoPlacementAccepted)
  {
    $this->instreamVideoPlacementAccepted = $instreamVideoPlacementAccepted;
  }
  /**
   * @return bool
   */
  public function getInstreamVideoPlacementAccepted()
  {
    return $this->instreamVideoPlacementAccepted;
  }
  /**
   * @param bool
   */
  public function setInterstitialPlacementAccepted($interstitialPlacementAccepted)
  {
    $this->interstitialPlacementAccepted = $interstitialPlacementAccepted;
  }
  /**
   * @return bool
   */
  public function getInterstitialPlacementAccepted()
  {
    return $this->interstitialPlacementAccepted;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DirectorySiteSettings::class, 'Google_Service_Dfareporting_DirectorySiteSettings');
