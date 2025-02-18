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

namespace Google\Service\ContainerAnalysis;

class UpgradeNote extends \Google\Collection
{
  protected $collection_key = 'distributions';
  protected $distributionsType = UpgradeDistribution::class;
  protected $distributionsDataType = 'array';
  /**
   * @var string
   */
  public $package;
  protected $versionType = Version::class;
  protected $versionDataType = '';
  protected $windowsUpdateType = WindowsUpdate::class;
  protected $windowsUpdateDataType = '';

  /**
   * @param UpgradeDistribution[]
   */
  public function setDistributions($distributions)
  {
    $this->distributions = $distributions;
  }
  /**
   * @return UpgradeDistribution[]
   */
  public function getDistributions()
  {
    return $this->distributions;
  }
  /**
   * @param string
   */
  public function setPackage($package)
  {
    $this->package = $package;
  }
  /**
   * @return string
   */
  public function getPackage()
  {
    return $this->package;
  }
  /**
   * @param Version
   */
  public function setVersion(Version $version)
  {
    $this->version = $version;
  }
  /**
   * @return Version
   */
  public function getVersion()
  {
    return $this->version;
  }
  /**
   * @param WindowsUpdate
   */
  public function setWindowsUpdate(WindowsUpdate $windowsUpdate)
  {
    $this->windowsUpdate = $windowsUpdate;
  }
  /**
   * @return WindowsUpdate
   */
  public function getWindowsUpdate()
  {
    return $this->windowsUpdate;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(UpgradeNote::class, 'Google_Service_ContainerAnalysis_UpgradeNote');
