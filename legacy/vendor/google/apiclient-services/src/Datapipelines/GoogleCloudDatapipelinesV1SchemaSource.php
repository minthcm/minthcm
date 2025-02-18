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

namespace Google\Service\Datapipelines;

class GoogleCloudDatapipelinesV1SchemaSource extends \Google\Model
{
  protected $localSchemaType = GoogleCloudDatapipelinesV1Schema::class;
  protected $localSchemaDataType = '';
  /**
   * @var string
   */
  public $referenceId;

  /**
   * @param GoogleCloudDatapipelinesV1Schema
   */
  public function setLocalSchema(GoogleCloudDatapipelinesV1Schema $localSchema)
  {
    $this->localSchema = $localSchema;
  }
  /**
   * @return GoogleCloudDatapipelinesV1Schema
   */
  public function getLocalSchema()
  {
    return $this->localSchema;
  }
  /**
   * @param string
   */
  public function setReferenceId($referenceId)
  {
    $this->referenceId = $referenceId;
  }
  /**
   * @return string
   */
  public function getReferenceId()
  {
    return $this->referenceId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDatapipelinesV1SchemaSource::class, 'Google_Service_Datapipelines_GoogleCloudDatapipelinesV1SchemaSource');
