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

namespace Google\Service\Contentwarehouse;

class GoogleCloudContentwarehouseV1DocumentReference extends \Google\Model
{
  /**
   * @var string
   */
  public $createTime;
  /**
   * @var string
   */
  public $deleteTime;
  /**
   * @var string
   */
  public $displayName;
  /**
   * @var bool
   */
  public $documentIsFolder;
  /**
   * @var bool
   */
  public $documentIsLegalHoldFolder;
  /**
   * @var bool
   */
  public $documentIsRetentionFolder;
  /**
   * @var string
   */
  public $documentName;
  /**
   * @var string
   */
  public $snippet;
  /**
   * @var string
   */
  public $updateTime;

  /**
   * @param string
   */
  public function setCreateTime($createTime)
  {
    $this->createTime = $createTime;
  }
  /**
   * @return string
   */
  public function getCreateTime()
  {
    return $this->createTime;
  }
  /**
   * @param string
   */
  public function setDeleteTime($deleteTime)
  {
    $this->deleteTime = $deleteTime;
  }
  /**
   * @return string
   */
  public function getDeleteTime()
  {
    return $this->deleteTime;
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
   * @param bool
   */
  public function setDocumentIsFolder($documentIsFolder)
  {
    $this->documentIsFolder = $documentIsFolder;
  }
  /**
   * @return bool
   */
  public function getDocumentIsFolder()
  {
    return $this->documentIsFolder;
  }
  /**
   * @param bool
   */
  public function setDocumentIsLegalHoldFolder($documentIsLegalHoldFolder)
  {
    $this->documentIsLegalHoldFolder = $documentIsLegalHoldFolder;
  }
  /**
   * @return bool
   */
  public function getDocumentIsLegalHoldFolder()
  {
    return $this->documentIsLegalHoldFolder;
  }
  /**
   * @param bool
   */
  public function setDocumentIsRetentionFolder($documentIsRetentionFolder)
  {
    $this->documentIsRetentionFolder = $documentIsRetentionFolder;
  }
  /**
   * @return bool
   */
  public function getDocumentIsRetentionFolder()
  {
    return $this->documentIsRetentionFolder;
  }
  /**
   * @param string
   */
  public function setDocumentName($documentName)
  {
    $this->documentName = $documentName;
  }
  /**
   * @return string
   */
  public function getDocumentName()
  {
    return $this->documentName;
  }
  /**
   * @param string
   */
  public function setSnippet($snippet)
  {
    $this->snippet = $snippet;
  }
  /**
   * @return string
   */
  public function getSnippet()
  {
    return $this->snippet;
  }
  /**
   * @param string
   */
  public function setUpdateTime($updateTime)
  {
    $this->updateTime = $updateTime;
  }
  /**
   * @return string
   */
  public function getUpdateTime()
  {
    return $this->updateTime;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudContentwarehouseV1DocumentReference::class, 'Google_Service_Contentwarehouse_GoogleCloudContentwarehouseV1DocumentReference');
