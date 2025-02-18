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

namespace Google\Service\Firestore;

class DocumentTransform extends \Google\Collection
{
  protected $collection_key = 'fieldTransforms';
  /**
   * @var string
   */
  public $document;
  protected $fieldTransformsType = FieldTransform::class;
  protected $fieldTransformsDataType = 'array';

  /**
   * @param string
   */
  public function setDocument($document)
  {
    $this->document = $document;
  }
  /**
   * @return string
   */
  public function getDocument()
  {
    return $this->document;
  }
  /**
   * @param FieldTransform[]
   */
  public function setFieldTransforms($fieldTransforms)
  {
    $this->fieldTransforms = $fieldTransforms;
  }
  /**
   * @return FieldTransform[]
   */
  public function getFieldTransforms()
  {
    return $this->fieldTransforms;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DocumentTransform::class, 'Google_Service_Firestore_DocumentTransform');
