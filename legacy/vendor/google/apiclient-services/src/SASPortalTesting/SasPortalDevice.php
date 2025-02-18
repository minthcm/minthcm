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

namespace Google\Service\SASPortalTesting;

class SasPortalDevice extends \Google\Collection
{
  protected $collection_key = 'grants';
  protected $activeConfigType = SasPortalDeviceConfig::class;
  protected $activeConfigDataType = '';
  protected $currentChannelsType = SasPortalChannelWithScore::class;
  protected $currentChannelsDataType = 'array';
  protected $deviceMetadataType = SasPortalDeviceMetadata::class;
  protected $deviceMetadataDataType = '';
  /**
   * @var string
   */
  public $displayName;
  /**
   * @var string
   */
  public $fccId;
  protected $grantRangeAllowlistsType = SasPortalFrequencyRange::class;
  protected $grantRangeAllowlistsDataType = 'array';
  protected $grantsType = SasPortalDeviceGrant::class;
  protected $grantsDataType = 'array';
  /**
   * @var string
   */
  public $name;
  protected $preloadedConfigType = SasPortalDeviceConfig::class;
  protected $preloadedConfigDataType = '';
  /**
   * @var string
   */
  public $serialNumber;
  /**
   * @var string
   */
  public $state;

  /**
   * @param SasPortalDeviceConfig
   */
  public function setActiveConfig(SasPortalDeviceConfig $activeConfig)
  {
    $this->activeConfig = $activeConfig;
  }
  /**
   * @return SasPortalDeviceConfig
   */
  public function getActiveConfig()
  {
    return $this->activeConfig;
  }
  /**
   * @param SasPortalChannelWithScore[]
   */
  public function setCurrentChannels($currentChannels)
  {
    $this->currentChannels = $currentChannels;
  }
  /**
   * @return SasPortalChannelWithScore[]
   */
  public function getCurrentChannels()
  {
    return $this->currentChannels;
  }
  /**
   * @param SasPortalDeviceMetadata
   */
  public function setDeviceMetadata(SasPortalDeviceMetadata $deviceMetadata)
  {
    $this->deviceMetadata = $deviceMetadata;
  }
  /**
   * @return SasPortalDeviceMetadata
   */
  public function getDeviceMetadata()
  {
    return $this->deviceMetadata;
  }
  /**
   * @param string
   */
  public function setDisplayName($displayName)
  {
    $this->displayName = $displayName;
  }
  /**
   * @return string
   */
  public function getDisplayName()
  {
    return $this->displayName;
  }
  /**
   * @param string
   */
  public function setFccId($fccId)
  {
    $this->fccId = $fccId;
  }
  /**
   * @return string
   */
  public function getFccId()
  {
    return $this->fccId;
  }
  /**
   * @param SasPortalFrequencyRange[]
   */
  public function setGrantRangeAllowlists($grantRangeAllowlists)
  {
    $this->grantRangeAllowlists = $grantRangeAllowlists;
  }
  /**
   * @return SasPortalFrequencyRange[]
   */
  public function getGrantRangeAllowlists()
  {
    return $this->grantRangeAllowlists;
  }
  /**
   * @param SasPortalDeviceGrant[]
   */
  public function setGrants($grants)
  {
    $this->grants = $grants;
  }
  /**
   * @return SasPortalDeviceGrant[]
   */
  public function getGrants()
  {
    return $this->grants;
  }
  /**
   * @param string
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
  /**
   * @param SasPortalDeviceConfig
   */
  public function setPreloadedConfig(SasPortalDeviceConfig $preloadedConfig)
  {
    $this->preloadedConfig = $preloadedConfig;
  }
  /**
   * @return SasPortalDeviceConfig
   */
  public function getPreloadedConfig()
  {
    return $this->preloadedConfig;
  }
  /**
   * @param string
   */
  public function setSerialNumber($serialNumber)
  {
    $this->serialNumber = $serialNumber;
  }
  /**
   * @return string
   */
  public function getSerialNumber()
  {
    return $this->serialNumber;
  }
  /**
   * @param string
   */
  public function setState($state)
  {
    $this->state = $state;
  }
  /**
   * @return string
   */
  public function getState()
  {
    return $this->state;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SasPortalDevice::class, 'Google_Service_SASPortalTesting_SasPortalDevice');
