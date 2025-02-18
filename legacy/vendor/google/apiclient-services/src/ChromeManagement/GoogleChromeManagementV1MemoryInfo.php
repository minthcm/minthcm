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

namespace Google\Service\ChromeManagement;

class GoogleChromeManagementV1MemoryInfo extends \Google\Model
{
  /**
   * @var string
   */
  public $availableRamBytes;
  protected $totalMemoryEncryptionType = GoogleChromeManagementV1TotalMemoryEncryptionInfo::class;
  protected $totalMemoryEncryptionDataType = '';
  /**
   * @var string
   */
  public $totalRamBytes;

  /**
   * @param string
   */
  public function setAvailableRamBytes($availableRamBytes)
  {
    $this->availableRamBytes = $availableRamBytes;
  }
  /**
   * @return string
   */
  public function getAvailableRamBytes()
  {
    return $this->availableRamBytes;
  }
  /**
   * @param GoogleChromeManagementV1TotalMemoryEncryptionInfo
   */
  public function setTotalMemoryEncryption(GoogleChromeManagementV1TotalMemoryEncryptionInfo $totalMemoryEncryption)
  {
    $this->totalMemoryEncryption = $totalMemoryEncryption;
  }
  /**
   * @return GoogleChromeManagementV1TotalMemoryEncryptionInfo
   */
  public function getTotalMemoryEncryption()
  {
    return $this->totalMemoryEncryption;
  }
  /**
   * @param string
   */
  public function setTotalRamBytes($totalRamBytes)
  {
    $this->totalRamBytes = $totalRamBytes;
  }
  /**
   * @return string
   */
  public function getTotalRamBytes()
  {
    return $this->totalRamBytes;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleChromeManagementV1MemoryInfo::class, 'Google_Service_ChromeManagement_GoogleChromeManagementV1MemoryInfo');
