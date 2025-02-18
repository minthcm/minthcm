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

namespace Google\Service\Document;

class GoogleCloudDocumentaiV1DocumentOutputConfigGcsOutputConfig extends \Google\Model
{
  /**
   * @var string
   */
  public $fieldMask;
  /**
   * @var string
   */
  public $gcsUri;
  protected $shardingConfigType = GoogleCloudDocumentaiV1DocumentOutputConfigGcsOutputConfigShardingConfig::class;
  protected $shardingConfigDataType = '';

  /**
   * @param string
   */
  public function setFieldMask($fieldMask)
  {
    $this->fieldMask = $fieldMask;
  }
  /**
   * @return string
   */
  public function getFieldMask()
  {
    return $this->fieldMask;
  }
  /**
   * @param string
   */
  public function setGcsUri($gcsUri)
  {
    $this->gcsUri = $gcsUri;
  }
  /**
   * @return string
   */
  public function getGcsUri()
  {
    return $this->gcsUri;
  }
  /**
   * @param GoogleCloudDocumentaiV1DocumentOutputConfigGcsOutputConfigShardingConfig
   */
  public function setShardingConfig(GoogleCloudDocumentaiV1DocumentOutputConfigGcsOutputConfigShardingConfig $shardingConfig)
  {
    $this->shardingConfig = $shardingConfig;
  }
  /**
   * @return GoogleCloudDocumentaiV1DocumentOutputConfigGcsOutputConfigShardingConfig
   */
  public function getShardingConfig()
  {
    return $this->shardingConfig;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDocumentaiV1DocumentOutputConfigGcsOutputConfig::class, 'Google_Service_Document_GoogleCloudDocumentaiV1DocumentOutputConfigGcsOutputConfig');
