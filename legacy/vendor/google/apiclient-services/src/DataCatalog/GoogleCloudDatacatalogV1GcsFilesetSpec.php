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

class GoogleCloudDatacatalogV1GcsFilesetSpec extends \Google\Collection
{
  protected $collection_key = 'sampleGcsFileSpecs';
  /**
   * @var string[]
   */
  public $filePatterns;
  protected $sampleGcsFileSpecsType = GoogleCloudDatacatalogV1GcsFileSpec::class;
  protected $sampleGcsFileSpecsDataType = 'array';

  /**
   * @param string[]
   */
  public function setFilePatterns($filePatterns)
  {
    $this->filePatterns = $filePatterns;
  }
  /**
   * @return string[]
   */
  public function getFilePatterns()
  {
    return $this->filePatterns;
  }
  /**
   * @param GoogleCloudDatacatalogV1GcsFileSpec[]
   */
  public function setSampleGcsFileSpecs($sampleGcsFileSpecs)
  {
    $this->sampleGcsFileSpecs = $sampleGcsFileSpecs;
  }
  /**
   * @return GoogleCloudDatacatalogV1GcsFileSpec[]
   */
  public function getSampleGcsFileSpecs()
  {
    return $this->sampleGcsFileSpecs;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDatacatalogV1GcsFilesetSpec::class, 'Google_Service_DataCatalog_GoogleCloudDatacatalogV1GcsFilesetSpec');
