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

namespace Google\Service\MigrationCenterAPI;

class FileValidationReport extends \Google\Collection
{
  protected $collection_key = 'rowErrors';
  protected $fileErrorsType = ImportError::class;
  protected $fileErrorsDataType = 'array';
  /**
   * @var string
   */
  public $fileName;
  /**
   * @var bool
   */
  public $partialReport;
  protected $rowErrorsType = ImportRowError::class;
  protected $rowErrorsDataType = 'array';

  /**
   * @param ImportError[]
   */
  public function setFileErrors($fileErrors)
  {
    $this->fileErrors = $fileErrors;
  }
  /**
   * @return ImportError[]
   */
  public function getFileErrors()
  {
    return $this->fileErrors;
  }
  /**
   * @param string
   */
  public function setFileName($fileName)
  {
    $this->fileName = $fileName;
  }
  /**
   * @return string
   */
  public function getFileName()
  {
    return $this->fileName;
  }
  /**
   * @param bool
   */
  public function setPartialReport($partialReport)
  {
    $this->partialReport = $partialReport;
  }
  /**
   * @return bool
   */
  public function getPartialReport()
  {
    return $this->partialReport;
  }
  /**
   * @param ImportRowError[]
   */
  public function setRowErrors($rowErrors)
  {
    $this->rowErrors = $rowErrors;
  }
  /**
   * @return ImportRowError[]
   */
  public function getRowErrors()
  {
    return $this->rowErrors;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(FileValidationReport::class, 'Google_Service_MigrationCenterAPI_FileValidationReport');
