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

class GoogleCloudDatapipelinesV1Field extends \Google\Model
{
  /**
   * @var string
   */
  public $name;
  protected $typeType = GoogleCloudDatapipelinesV1FieldType::class;
  protected $typeDataType = '';

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
  /**
   * @param GoogleCloudDatapipelinesV1FieldType
   */
  public function setType(GoogleCloudDatapipelinesV1FieldType $type)
  {
    $this->type = $type;
  }
  /**
   * @return GoogleCloudDatapipelinesV1FieldType
   */
  public function getType()
  {
    return $this->type;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDatapipelinesV1Field::class, 'Google_Service_Datapipelines_GoogleCloudDatapipelinesV1Field');
