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

class MailExportOptions extends \Google\Model
{
  /**
   * @var string
   */
  public $exportFormat;
  /**
   * @var bool
   */
  public $exportLinkedDriveFiles;
  /**
   * @var bool
   */
  public $showConfidentialModeContent;
  /**
   * @var bool
   */
  public $useNewExport;

  /**
   * @param string
   */
  public function setExportFormat($exportFormat)
  {
    $this->exportFormat = $exportFormat;
  }
  /**
   * @return string
   */
  public function getExportFormat()
  {
    return $this->exportFormat;
  }
  /**
   * @param bool
   */
  public function setExportLinkedDriveFiles($exportLinkedDriveFiles)
  {
    $this->exportLinkedDriveFiles = $exportLinkedDriveFiles;
  }
  /**
   * @return bool
   */
  public function getExportLinkedDriveFiles()
  {
    return $this->exportLinkedDriveFiles;
  }
  /**
   * @param bool
   */
  public function setShowConfidentialModeContent($showConfidentialModeContent)
  {
    $this->showConfidentialModeContent = $showConfidentialModeContent;
  }
  /**
   * @return bool
   */
  public function getShowConfidentialModeContent()
  {
    return $this->showConfidentialModeContent;
  }
  /**
   * @param bool
   */
  public function setUseNewExport($useNewExport)
  {
    $this->useNewExport = $useNewExport;
  }
  /**
   * @return bool
   */
  public function getUseNewExport()
  {
    return $this->useNewExport;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(MailExportOptions::class, 'Google_Service_Vault_MailExportOptions');
