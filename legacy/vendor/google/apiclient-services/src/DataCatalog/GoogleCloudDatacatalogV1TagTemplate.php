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

namespace Google\Service\DataCatalog;

class GoogleCloudDatacatalogV1TagTemplate extends \Google\Model
{
  /**
   * @var string
   */
  public $dataplexTransferStatus;
  /**
   * @var string
   */
  public $displayName;
  protected $fieldsType = GoogleCloudDatacatalogV1TagTemplateField::class;
  protected $fieldsDataType = 'map';
  /**
   * @var bool
   */
  public $isPubliclyReadable;
  /**
   * @var string
   */
  public $name;

  /**
   * @param string
   */
  public function setDataplexTransferStatus($dataplexTransferStatus)
  {
    $this->dataplexTransferStatus = $dataplexTransferStatus;
  }
  /**
   * @return string
   */
  public function getDataplexTransferStatus()
  {
    return $this->dataplexTransferStatus;
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
   * @param GoogleCloudDatacatalogV1TagTemplateField[]
   */
  public function setFields($fields)
  {
    $this->fields = $fields;
  }
  /**
   * @return GoogleCloudDatacatalogV1TagTemplateField[]
   */
  public function getFields()
  {
    return $this->fields;
  }
  /**
   * @param bool
   */
  public function setIsPubliclyReadable($isPubliclyReadable)
  {
    $this->isPubliclyReadable = $isPubliclyReadable;
  }
  /**
   * @return bool
   */
  public function getIsPubliclyReadable()
  {
    return $this->isPubliclyReadable;
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
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDatacatalogV1TagTemplate::class, 'Google_Service_DataCatalog_GoogleCloudDatacatalogV1TagTemplate');
