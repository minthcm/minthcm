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

class GoogleCloudDatacatalogV1beta1Tag extends \Google\Model
{
  public $column;
  protected $fieldsType = GoogleCloudDatacatalogV1beta1TagField::class;
  protected $fieldsDataType = 'map';
  public $name;
  public $template;
  public $templateDisplayName;

  public function setColumn($column)
  {
    $this->column = $column;
  }
  public function getColumn()
  {
    return $this->column;
  }
  /**
   * @param GoogleCloudDatacatalogV1beta1TagField[]
   */
  public function setFields($fields)
  {
    $this->fields = $fields;
  }
  /**
   * @return GoogleCloudDatacatalogV1beta1TagField[]
   */
  public function getFields()
  {
    return $this->fields;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setTemplate($template)
  {
    $this->template = $template;
  }
  public function getTemplate()
  {
    return $this->template;
  }
  public function setTemplateDisplayName($templateDisplayName)
  {
    $this->templateDisplayName = $templateDisplayName;
  }
  public function getTemplateDisplayName()
  {
    return $this->templateDisplayName;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDatacatalogV1beta1Tag::class, 'Google_Service_DataCatalog_GoogleCloudDatacatalogV1beta1Tag');
