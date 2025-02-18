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

namespace Google\Service\Vault;

class DriveOptions extends \Google\Model
{
  /**
   * @var string
   */
  public $clientSideEncryptedOption;
  /**
   * @var bool
   */
  public $includeSharedDrives;
  /**
   * @var bool
   */
  public $includeTeamDrives;
  /**
   * @var string
   */
  public $versionDate;

  /**
   * @param string
   */
  public function setClientSideEncryptedOption($clientSideEncryptedOption)
  {
    $this->clientSideEncryptedOption = $clientSideEncryptedOption;
  }
  /**
   * @return string
   */
  public function getClientSideEncryptedOption()
  {
    return $this->clientSideEncryptedOption;
  }
  /**
   * @param bool
   */
  public function setIncludeSharedDrives($includeSharedDrives)
  {
    $this->includeSharedDrives = $includeSharedDrives;
  }
  /**
   * @return bool
   */
  public function getIncludeSharedDrives()
  {
    return $this->includeSharedDrives;
  }
  /**
   * @param bool
   */
  public function setIncludeTeamDrives($includeTeamDrives)
  {
    $this->includeTeamDrives = $includeTeamDrives;
  }
  /**
   * @return bool
   */
  public function getIncludeTeamDrives()
  {
    return $this->includeTeamDrives;
  }
  /**
   * @param string
   */
  public function setVersionDate($versionDate)
  {
    $this->versionDate = $versionDate;
  }
  /**
   * @return string
   */
  public function getVersionDate()
  {
    return $this->versionDate;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DriveOptions::class, 'Google_Service_Vault_DriveOptions');
