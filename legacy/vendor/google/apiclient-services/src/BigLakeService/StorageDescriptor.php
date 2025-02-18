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

namespace Google\Service\BigLakeService;

class StorageDescriptor extends \Google\Model
{
  /**
   * @var string
   */
  public $inputFormat;
  /**
   * @var string
   */
  public $locationUri;
  /**
   * @var string
   */
  public $outputFormat;
  protected $serdeInfoType = SerDeInfo::class;
  protected $serdeInfoDataType = '';

  /**
   * @param string
   */
  public function setInputFormat($inputFormat)
  {
    $this->inputFormat = $inputFormat;
  }
  /**
   * @return string
   */
  public function getInputFormat()
  {
    return $this->inputFormat;
  }
  /**
   * @param string
   */
  public function setLocationUri($locationUri)
  {
    $this->locationUri = $locationUri;
  }
  /**
   * @return string
   */
  public function getLocationUri()
  {
    return $this->locationUri;
  }
  /**
   * @param string
   */
  public function setOutputFormat($outputFormat)
  {
    $this->outputFormat = $outputFormat;
  }
  /**
   * @return string
   */
  public function getOutputFormat()
  {
    return $this->outputFormat;
  }
  /**
   * @param SerDeInfo
   */
  public function setSerdeInfo(SerDeInfo $serdeInfo)
  {
    $this->serdeInfo = $serdeInfo;
  }
  /**
   * @return SerDeInfo
   */
  public function getSerdeInfo()
  {
    return $this->serdeInfo;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(StorageDescriptor::class, 'Google_Service_BigLakeService_StorageDescriptor');
