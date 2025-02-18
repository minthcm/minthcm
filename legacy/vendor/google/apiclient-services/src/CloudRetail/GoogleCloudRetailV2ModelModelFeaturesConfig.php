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

class GoogleCloudRetailV2ModelModelFeaturesConfig extends \Google\Model
{
  protected $frequentlyBoughtTogetherConfigType = GoogleCloudRetailV2ModelFrequentlyBoughtTogetherFeaturesConfig::class;
  protected $frequentlyBoughtTogetherConfigDataType = '';

  /**
   * @param GoogleCloudRetailV2ModelFrequentlyBoughtTogetherFeaturesConfig
   */
  public function setFrequentlyBoughtTogetherConfig(GoogleCloudRetailV2ModelFrequentlyBoughtTogetherFeaturesConfig $frequentlyBoughtTogetherConfig)
  {
    $this->frequentlyBoughtTogetherConfig = $frequentlyBoughtTogetherConfig;
  }
  /**
   * @return GoogleCloudRetailV2ModelFrequentlyBoughtTogetherFeaturesConfig
   */
  public function getFrequentlyBoughtTogetherConfig()
  {
    return $this->frequentlyBoughtTogetherConfig;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudRetailV2ModelModelFeaturesConfig::class, 'Google_Service_CloudRetail_GoogleCloudRetailV2ModelModelFeaturesConfig');
