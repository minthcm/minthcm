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

namespace Google\Service\Bigquery;

class StandardSqlDataType extends \Google\Model
{
  protected $arrayElementTypeType = StandardSqlDataType::class;
  protected $arrayElementTypeDataType = '';
  protected $rangeElementTypeType = StandardSqlDataType::class;
  protected $rangeElementTypeDataType = '';
  protected $structTypeType = StandardSqlStructType::class;
  protected $structTypeDataType = '';
  /**
   * @var string
   */
  public $typeKind;

  /**
   * @param StandardSqlDataType
   */
  public function setArrayElementType(StandardSqlDataType $arrayElementType)
  {
    $this->arrayElementType = $arrayElementType;
  }
  /**
   * @return StandardSqlDataType
   */
  public function getArrayElementType()
  {
    return $this->arrayElementType;
  }
  /**
   * @param StandardSqlDataType
   */
  public function setRangeElementType(StandardSqlDataType $rangeElementType)
  {
    $this->rangeElementType = $rangeElementType;
  }
  /**
   * @return StandardSqlDataType
   */
  public function getRangeElementType()
  {
    return $this->rangeElementType;
  }
  /**
   * @param StandardSqlStructType
   */
  public function setStructType(StandardSqlStructType $structType)
  {
    $this->structType = $structType;
  }
  /**
   * @return StandardSqlStructType
   */
  public function getStructType()
  {
    return $this->structType;
  }
  /**
   * @param string
   */
  public function setTypeKind($typeKind)
  {
    $this->typeKind = $typeKind;
  }
  /**
   * @return string
   */
  public function getTypeKind()
  {
    return $this->typeKind;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(StandardSqlDataType::class, 'Google_Service_Bigquery_StandardSqlDataType');
