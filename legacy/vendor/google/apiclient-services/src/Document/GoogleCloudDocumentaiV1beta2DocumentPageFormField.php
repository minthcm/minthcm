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

class GoogleCloudDocumentaiV1beta2DocumentPageFormField extends \Google\Collection
{
  protected $collection_key = 'valueDetectedLanguages';
  /**
   * @var string
   */
  public $correctedKeyText;
  /**
   * @var string
   */
  public $correctedValueText;
  protected $fieldNameType = GoogleCloudDocumentaiV1beta2DocumentPageLayout::class;
  protected $fieldNameDataType = '';
  protected $fieldValueType = GoogleCloudDocumentaiV1beta2DocumentPageLayout::class;
  protected $fieldValueDataType = '';
  protected $nameDetectedLanguagesType = GoogleCloudDocumentaiV1beta2DocumentPageDetectedLanguage::class;
  protected $nameDetectedLanguagesDataType = 'array';
  protected $provenanceType = GoogleCloudDocumentaiV1beta2DocumentProvenance::class;
  protected $provenanceDataType = '';
  protected $valueDetectedLanguagesType = GoogleCloudDocumentaiV1beta2DocumentPageDetectedLanguage::class;
  protected $valueDetectedLanguagesDataType = 'array';
  /**
   * @var string
   */
  public $valueType;

  /**
   * @param string
   */
  public function setCorrectedKeyText($correctedKeyText)
  {
    $this->correctedKeyText = $correctedKeyText;
  }
  /**
   * @return string
   */
  public function getCorrectedKeyText()
  {
    return $this->correctedKeyText;
  }
  /**
   * @param string
   */
  public function setCorrectedValueText($correctedValueText)
  {
    $this->correctedValueText = $correctedValueText;
  }
  /**
   * @return string
   */
  public function getCorrectedValueText()
  {
    return $this->correctedValueText;
  }
  /**
   * @param GoogleCloudDocumentaiV1beta2DocumentPageLayout
   */
  public function setFieldName(GoogleCloudDocumentaiV1beta2DocumentPageLayout $fieldName)
  {
    $this->fieldName = $fieldName;
  }
  /**
   * @return GoogleCloudDocumentaiV1beta2DocumentPageLayout
   */
  public function getFieldName()
  {
    return $this->fieldName;
  }
  /**
   * @param GoogleCloudDocumentaiV1beta2DocumentPageLayout
   */
  public function setFieldValue(GoogleCloudDocumentaiV1beta2DocumentPageLayout $fieldValue)
  {
    $this->fieldValue = $fieldValue;
  }
  /**
   * @return GoogleCloudDocumentaiV1beta2DocumentPageLayout
   */
  public function getFieldValue()
  {
    return $this->fieldValue;
  }
  /**
   * @param GoogleCloudDocumentaiV1beta2DocumentPageDetectedLanguage[]
   */
  public function setNameDetectedLanguages($nameDetectedLanguages)
  {
    $this->nameDetectedLanguages = $nameDetectedLanguages;
  }
  /**
   * @return GoogleCloudDocumentaiV1beta2DocumentPageDetectedLanguage[]
   */
  public function getNameDetectedLanguages()
  {
    return $this->nameDetectedLanguages;
  }
  /**
   * @param GoogleCloudDocumentaiV1beta2DocumentProvenance
   */
  public function setProvenance(GoogleCloudDocumentaiV1beta2DocumentProvenance $provenance)
  {
    $this->provenance = $provenance;
  }
  /**
   * @return GoogleCloudDocumentaiV1beta2DocumentProvenance
   */
  public function getProvenance()
  {
    return $this->provenance;
  }
  /**
   * @param GoogleCloudDocumentaiV1beta2DocumentPageDetectedLanguage[]
   */
  public function setValueDetectedLanguages($valueDetectedLanguages)
  {
    $this->valueDetectedLanguages = $valueDetectedLanguages;
  }
  /**
   * @return GoogleCloudDocumentaiV1beta2DocumentPageDetectedLanguage[]
   */
  public function getValueDetectedLanguages()
  {
    return $this->valueDetectedLanguages;
  }
  /**
   * @param string
   */
  public function setValueType($valueType)
  {
    $this->valueType = $valueType;
  }
  /**
   * @return string
   */
  public function getValueType()
  {
    return $this->valueType;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDocumentaiV1beta2DocumentPageFormField::class, 'Google_Service_Document_GoogleCloudDocumentaiV1beta2DocumentPageFormField');
