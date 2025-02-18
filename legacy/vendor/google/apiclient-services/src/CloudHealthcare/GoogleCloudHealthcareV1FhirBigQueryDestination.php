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

namespace Google\Service\CloudHealthcare;

class GoogleCloudHealthcareV1FhirBigQueryDestination extends \Google\Model
{
  /**
   * @var string
   */
  public $datasetUri;
  /**
   * @var bool
   */
  public $force;
  protected $schemaConfigType = SchemaConfig::class;
  protected $schemaConfigDataType = '';
  /**
   * @var string
   */
  public $writeDisposition;

  /**
   * @param string
   */
  public function setDatasetUri($datasetUri)
  {
    $this->datasetUri = $datasetUri;
  }
  /**
   * @return string
   */
  public function getDatasetUri()
  {
    return $this->datasetUri;
  }
  /**
   * @param bool
   */
  public function setForce($force)
  {
    $this->force = $force;
  }
  /**
   * @return bool
   */
  public function getForce()
  {
    return $this->force;
  }
  /**
   * @param SchemaConfig
   */
  public function setSchemaConfig(SchemaConfig $schemaConfig)
  {
    $this->schemaConfig = $schemaConfig;
  }
  /**
   * @return SchemaConfig
   */
  public function getSchemaConfig()
  {
    return $this->schemaConfig;
  }
  /**
   * @param string
   */
  public function setWriteDisposition($writeDisposition)
  {
    $this->writeDisposition = $writeDisposition;
  }
  /**
   * @return string
   */
  public function getWriteDisposition()
  {
    return $this->writeDisposition;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudHealthcareV1FhirBigQueryDestination::class, 'Google_Service_CloudHealthcare_GoogleCloudHealthcareV1FhirBigQueryDestination');
