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

class GoogleCloudDocumentaiV1beta1DocumentPageToken extends \Google\Collection
{
  protected $collection_key = 'detectedLanguages';
  protected $detectedBreakType = GoogleCloudDocumentaiV1beta1DocumentPageTokenDetectedBreak::class;
  protected $detectedBreakDataType = '';
  protected $detectedLanguagesType = GoogleCloudDocumentaiV1beta1DocumentPageDetectedLanguage::class;
  protected $detectedLanguagesDataType = 'array';
  protected $layoutType = GoogleCloudDocumentaiV1beta1DocumentPageLayout::class;
  protected $layoutDataType = '';
  protected $provenanceType = GoogleCloudDocumentaiV1beta1DocumentProvenance::class;
  protected $provenanceDataType = '';
  protected $styleInfoType = GoogleCloudDocumentaiV1beta1DocumentPageTokenStyleInfo::class;
  protected $styleInfoDataType = '';

  /**
   * @param GoogleCloudDocumentaiV1beta1DocumentPageTokenDetectedBreak
   */
  public function setDetectedBreak(GoogleCloudDocumentaiV1beta1DocumentPageTokenDetectedBreak $detectedBreak)
  {
    $this->detectedBreak = $detectedBreak;
  }
  /**
   * @return GoogleCloudDocumentaiV1beta1DocumentPageTokenDetectedBreak
   */
  public function getDetectedBreak()
  {
    return $this->detectedBreak;
  }
  /**
   * @param GoogleCloudDocumentaiV1beta1DocumentPageDetectedLanguage[]
   */
  public function setDetectedLanguages($detectedLanguages)
  {
    $this->detectedLanguages = $detectedLanguages;
  }
  /**
   * @return GoogleCloudDocumentaiV1beta1DocumentPageDetectedLanguage[]
   */
  public function getDetectedLanguages()
  {
    return $this->detectedLanguages;
  }
  /**
   * @param GoogleCloudDocumentaiV1beta1DocumentPageLayout
   */
  public function setLayout(GoogleCloudDocumentaiV1beta1DocumentPageLayout $layout)
  {
    $this->layout = $layout;
  }
  /**
   * @return GoogleCloudDocumentaiV1beta1DocumentPageLayout
   */
  public function getLayout()
  {
    return $this->layout;
  }
  /**
   * @param GoogleCloudDocumentaiV1beta1DocumentProvenance
   */
  public function setProvenance(GoogleCloudDocumentaiV1beta1DocumentProvenance $provenance)
  {
    $this->provenance = $provenance;
  }
  /**
   * @return GoogleCloudDocumentaiV1beta1DocumentProvenance
   */
  public function getProvenance()
  {
    return $this->provenance;
  }
  /**
   * @param GoogleCloudDocumentaiV1beta1DocumentPageTokenStyleInfo
   */
  public function setStyleInfo(GoogleCloudDocumentaiV1beta1DocumentPageTokenStyleInfo $styleInfo)
  {
    $this->styleInfo = $styleInfo;
  }
  /**
   * @return GoogleCloudDocumentaiV1beta1DocumentPageTokenStyleInfo
   */
  public function getStyleInfo()
  {
    return $this->styleInfo;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDocumentaiV1beta1DocumentPageToken::class, 'Google_Service_Document_GoogleCloudDocumentaiV1beta1DocumentPageToken');
